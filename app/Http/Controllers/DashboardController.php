<?php

namespace App\Http\Controllers;

use App\Models\DriveFile;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $folder = null;

        return $this->renderDrive($request, $folder, 'dashboard');
    }

    public function folder(Request $request, Folder $folder): View
    {
        abort_unless($folder->user_id === $request->user()->id, 404);

        return $this->renderDrive($request, $folder, 'folder');
    }

    public function favorites(Request $request): View
    {
        $user = $request->user();
        $query = trim((string) $request->string('q'));

        $folders = Folder::ownedBy($user)
            ->where('is_favorite', true)
            ->when($query !== '', fn ($builder) => $builder->where('name', 'like', "%{$query}%"))
            ->latest('updated_at')
            ->get();

        $files = DriveFile::ownedBy($user)
            ->where('is_favorite', true)
            ->when($query !== '', fn ($builder) => $builder->where('original_name', 'like', "%{$query}%"))
            ->latest('updated_at')
            ->get();

        return view('drive.favorites', [
            'query' => $query,
            'folders' => $folders,
            'files' => $files,
            'stats' => $this->buildStats($request),
        ]);
    }

    public function trash(Request $request): View
    {
        $user = $request->user();

        return view('drive.trash', [
            'folders' => Folder::onlyTrashed()->ownedBy($user)->latest('deleted_at')->get(),
            'files' => DriveFile::onlyTrashed()->ownedBy($user)->latest('deleted_at')->get(),
            'stats' => $this->buildStats($request),
        ]);
    }

    protected function renderDrive(Request $request, ?Folder $folder, string $mode): View
    {
        $user = $request->user();
        $query = trim((string) $request->string('q'));

        $foldersQuery = Folder::ownedBy($user)->where('parent_id', $folder?->id);
        $filesQuery = DriveFile::ownedBy($user)->where('folder_id', $folder?->id);

        if ($query !== '') {
            $foldersQuery->where('name', 'like', "%{$query}%");
            $filesQuery->where('original_name', 'like', "%{$query}%");
        }

        return view('drive.index', [
            'mode' => $mode,
            'currentFolder' => $folder,
            'breadcrumbs' => $this->breadcrumbs($folder),
            'folders' => $foldersQuery->orderBy('name')->get(),
            'files' => $filesQuery->orderByDesc('updated_at')->get(),
            'stats' => $this->buildStats($request),
            'recentUploads' => DriveFile::ownedBy($user)->latest()->limit(5)->get(),
            'query' => $query,
            'allFolders' => Folder::ownedBy($user)->orderBy('name')->get(),
        ]);
    }

    protected function buildStats(Request $request): array
    {
        $user = $request->user();
        $storage = (int) DriveFile::ownedBy($user)->sum('size');

        return [
            'files' => DriveFile::ownedBy($user)->count(),
            'folders' => Folder::ownedBy($user)->count(),
            'favorites' => DriveFile::ownedBy($user)->where('is_favorite', true)->count()
                + Folder::ownedBy($user)->where('is_favorite', true)->count(),
            'trash' => DriveFile::onlyTrashed()->ownedBy($user)->count()
                + Folder::onlyTrashed()->ownedBy($user)->count(),
            'storage_used' => $storage,
            'storage_used_human' => $this->humanBytes($storage),
            'storage_limit' => 5 * 1024 * 1024 * 1024,
        ];
    }

    protected function breadcrumbs(?Folder $folder): Collection
    {
        $items = collect();

        while ($folder) {
            $items->prepend($folder);
            $folder = $folder->parent;
        }

        return $items;
    }

    protected function humanBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? (int) floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);
        $value = $bytes / (1024 ** $power);

        return number_format($value, $power === 0 ? 0 : 1).' '.$units[$power];
    }
}
