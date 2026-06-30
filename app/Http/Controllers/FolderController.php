<?php

namespace App\Http\Controllers;

use App\Models\DriveFile;
use App\Models\Folder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parent = $this->folderFromRequest($request, $data['parent_id'] ?? null);

        Folder::create([
            'user_id' => $request->user()->id,
            'parent_id' => $parent?->id,
            'name' => $data['name'],
        ]);

        return back()->with('status', 'Folder created.');
    }

    public function update(Request $request, Folder $folder): RedirectResponse
    {
        $this->authorizeFolder($request, $folder);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $folder->update($data);

        return back()->with('status', 'Folder renamed.');
    }

    public function favorite(Request $request, Folder $folder): RedirectResponse
    {
        $this->authorizeFolder($request, $folder);

        $folder->update(['is_favorite' => ! $folder->is_favorite]);

        return back()->with('status', $folder->is_favorite ? 'Folder added to favorites.' : 'Folder removed from favorites.');
    }

    public function move(Request $request, Folder $folder): RedirectResponse
    {
        $this->authorizeFolder($request, $folder);

        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parent = $this->folderFromRequest($request, $data['parent_id'] ?? null);

        if ($parent && ($parent->id === $folder->id || $this->isDescendant($parent, $folder))) {
            return back()->withErrors(['parent_id' => 'You cannot move a folder into itself or one of its children.']);
        }

        $folder->update(['parent_id' => $parent?->id]);

        return back()->with('status', 'Folder moved.');
    }

    public function copy(Request $request, Folder $folder): RedirectResponse
    {
        $this->authorizeFolder($request, $folder);

        $data = $request->validate([
            'parent_id' => ['nullable', 'integer'],
        ]);

        $parent = $this->folderFromRequest($request, $data['parent_id'] ?? null);

        DB::transaction(function () use ($request, $folder, $parent): void {
            $this->duplicateFolderTree($request->user()->id, $folder, $parent?->id);
        });

        return back()->with('status', 'Folder copied.');
    }

    public function destroy(Request $request, Folder $folder): RedirectResponse
    {
        $this->authorizeFolder($request, $folder);

        DB::transaction(fn () => $this->trashFolderTree($folder));

        return back()->with('status', 'Folder moved to trash.');
    }

    public function restore(Request $request, int $folderId): RedirectResponse
    {
        $folder = Folder::onlyTrashed()->findOrFail($folderId);
        $this->authorizeFolder($request, $folder);

        DB::transaction(function () use ($folder): void {
            $this->restoreFolderTree($folder);
        });

        return back()->with('status', 'Folder restored.');
    }

    public function forceDestroy(Request $request, int $folderId): RedirectResponse
    {
        $folder = Folder::withTrashed()->findOrFail($folderId);
        $this->authorizeFolder($request, $folder);

        DB::transaction(function () use ($folder): void {
            $this->deleteFolderTree($folder);
        });

        return back()->with('status', 'Folder permanently deleted.');
    }

    protected function duplicateFolderTree(int $userId, Folder $source, ?int $parentId = null): Folder
    {
        $copy = Folder::create([
            'user_id' => $userId,
            'parent_id' => $parentId,
            'name' => $source->name.' Copy',
            'is_favorite' => false,
        ]);

        foreach ($source->files as $file) {
            $newPath = 'uploads/'.Str::uuid().'_'.$file->stored_name;
            Storage::disk($file->disk)->copy($file->path, $newPath);

            DriveFile::create([
                'user_id' => $userId,
                'folder_id' => $copy->id,
                'original_name' => $file->original_name,
                'stored_name' => basename($newPath),
                'disk' => $file->disk,
                'path' => $newPath,
                'mime_type' => $file->mime_type,
                'extension' => $file->extension,
                'size' => $file->size,
            ]);
        }

        foreach ($source->children as $child) {
            $this->duplicateFolderTree($userId, $child, $copy->id);
        }

        return $copy;
    }

    protected function trashFolderTree(Folder $folder): void
    {
        $folder->loadMissing(['children', 'files']);

        foreach ($folder->children as $child) {
            $this->trashFolderTree($child);
        }

        foreach ($folder->files as $file) {
            $file->delete();
        }

        $folder->delete();
    }

    protected function restoreFolderTree(Folder $folder): void
    {
        $folder->restore();

        $parent = $folder->parent_id ? Folder::withTrashed()->find($folder->parent_id) : null;

        if ($parent && $parent->trashed()) {
            $folder->parent_id = null;
            $folder->save();
        }

        $folder->children()->onlyTrashed()->get()->each(function (Folder $child): void {
            $this->restoreFolderTree($child);
        });

        $folder->files()->onlyTrashed()->get()->each->restore();
    }

    protected function deleteFolderTree(Folder $folder): void
    {
        $folder->loadMissing(['children' => fn ($query) => $query->withTrashed(), 'files' => fn ($query) => $query->withTrashed()]);

        foreach ($folder->children as $child) {
            $this->deleteFolderTree($child);
        }

        foreach ($folder->files as $file) {
            Storage::disk($file->disk)->delete($file->path);
            $file->forceDelete();
        }

        $folder->forceDelete();
    }

    protected function isDescendant(Folder $candidateParent, Folder $folder): bool
    {
        $current = $candidateParent;

        while ($current) {
            if ($current->id === $folder->id) {
                return true;
            }

            $current = $current->parent;
        }

        return false;
    }

    protected function folderFromRequest(Request $request, ?int $folderId): ?Folder
    {
        if (! $folderId) {
            return null;
        }

        $folder = Folder::findOrFail($folderId);
        $this->authorizeFolder($request, $folder);

        return $folder;
    }

    protected function authorizeFolder(Request $request, Folder $folder): void
    {
        abort_unless($folder->user_id === $request->user()->id, 404);
    }
}
