<?php

namespace App\Http\Controllers;

use App\Models\DriveFile;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Uploads are sent one file per request by the upload-queue UI so each
     * file gets its own progress bar and a slow/large file can't block or
     * time out the others.
     */
    public const MAX_UPLOAD_KB = 2097152; // 2GB

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'folder_id' => ['nullable', 'integer'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['required', 'file', 'max:'.self::MAX_UPLOAD_KB],
        ]);

        $folder = $this->folderFromRequest($request, $data['folder_id'] ?? null);

        $created = [];

        foreach ($request->file('files', []) as $upload) {
            $storedName = Str::uuid().'.'.$upload->getClientOriginalExtension();
            $path = $upload->storeAs('uploads', $storedName, 'public');

            $created[] = DriveFile::create([
                'user_id' => $request->user()->id,
                'folder_id' => $folder?->id,
                'original_name' => $upload->getClientOriginalName(),
                'stored_name' => $storedName,
                'disk' => 'public',
                'path' => $path,
                'mime_type' => $upload->getMimeType() ?: $upload->getClientMimeType(),
                'extension' => $upload->getClientOriginalExtension(),
                'size' => $upload->getSize(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['status' => 'ok', 'files' => $created]);
        }

        return back()->with('status', 'Files uploaded.');
    }

    public function show(Request $request, DriveFile $file): View
    {
        $this->authorizeFile($request, $file);

        $previewText = null;

        if ($file->isText()) {
            $previewText = Str::limit(Storage::disk($file->disk)->get($file->path), 200000, "\n\n... truncated");
        }

        return view('drive.preview', [
            'file' => $file,
            'previewText' => $previewText,
        ]);
    }

    public function content(Request $request, DriveFile $file): BinaryFileResponse|StreamedResponse
    {
        $this->authorizeFile($request, $file);

        $disk = Storage::disk($file->disk);

        abort_unless($disk->exists($file->path), 404);

        $headers = [
            'Content-Type' => $file->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="'.addslashes($file->original_name).'"',
        ];

        // Local disks are served as a real file so Symfony can honor HTTP Range
        // requests, which video/audio players need for seeking and which
        // Safari/iOS require just to start playback at all.
        if (config("filesystems.disks.{$file->disk}.driver") === 'local') {
            return response()->file($disk->path($file->path), $headers);
        }

        return $disk->response($file->path, $file->original_name, $headers);
    }

    public function update(Request $request, DriveFile $file): RedirectResponse
    {
        $this->authorizeFile($request, $file);

        $data = $request->validate([
            'original_name' => ['required', 'string', 'max:255'],
        ]);

        $file->update($data);

        return back()->with('status', 'File renamed.');
    }

    public function favorite(Request $request, DriveFile $file): RedirectResponse
    {
        $this->authorizeFile($request, $file);

        $file->update(['is_favorite' => ! $file->is_favorite]);

        return back()->with('status', $file->is_favorite ? 'File added to favorites.' : 'File removed from favorites.');
    }

    public function move(Request $request, DriveFile $file): RedirectResponse
    {
        $this->authorizeFile($request, $file);

        $data = $request->validate([
            'folder_id' => ['nullable', 'integer'],
        ]);

        $folder = $this->folderFromRequest($request, $data['folder_id'] ?? null);

        $file->update(['folder_id' => $folder?->id]);

        return back()->with('status', 'File moved.');
    }

    public function copy(Request $request, DriveFile $file): RedirectResponse
    {
        $this->authorizeFile($request, $file);

        $data = $request->validate([
            'folder_id' => ['nullable', 'integer'],
        ]);

        $folder = $this->folderFromRequest($request, $data['folder_id'] ?? null);
        $newPath = 'uploads/'.Str::uuid().'_'.$file->stored_name;
        Storage::disk($file->disk)->copy($file->path, $newPath);

        DriveFile::create([
            'user_id' => $request->user()->id,
            'folder_id' => $folder?->id,
            'original_name' => pathinfo($file->original_name, PATHINFO_FILENAME).' Copy'.($file->extension ? '.'.$file->extension : ''),
            'stored_name' => basename($newPath),
            'disk' => $file->disk,
            'path' => $newPath,
            'mime_type' => $file->mime_type,
            'extension' => $file->extension,
            'size' => $file->size,
        ]);

        return back()->with('status', 'File copied.');
    }

    public function destroy(Request $request, DriveFile $file): RedirectResponse
    {
        $this->authorizeFile($request, $file);

        $file->delete();

        return back()->with('status', 'File moved to trash.');
    }

    public function restore(Request $request, int $fileId): RedirectResponse
    {
        $file = DriveFile::onlyTrashed()->findOrFail($fileId);
        $this->authorizeFile($request, $file);

        $folder = $file->folder_id ? Folder::withTrashed()->find($file->folder_id) : null;

        if ($folder && $folder->trashed()) {
            $file->folder_id = null;
            $file->save();
        }

        $file->restore();

        return back()->with('status', 'File restored.');
    }

    public function forceDestroy(Request $request, int $fileId): RedirectResponse
    {
        $file = DriveFile::withTrashed()->findOrFail($fileId);
        $this->authorizeFile($request, $file);

        Storage::disk($file->disk)->delete($file->path);
        $file->forceDelete();

        return back()->with('status', 'File permanently deleted.');
    }

    public function download(Request $request, DriveFile $file)
    {
        $this->authorizeFile($request, $file);

        abort_unless(Storage::disk($file->disk)->exists($file->path), 404);

        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    protected function folderFromRequest(Request $request, ?int $folderId): ?Folder
    {
        if (! $folderId) {
            return null;
        }

        $folder = Folder::findOrFail($folderId);
        abort_unless($folder->user_id === $request->user()->id, 404);

        return $folder;
    }

    protected function authorizeFile(Request $request, DriveFile $file): void
    {
        abort_unless($file->user_id === $request->user()->id, 404);
    }
}
