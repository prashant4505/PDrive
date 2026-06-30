<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="grid gap-4 lg:grid-cols-[1.8fr_1fr]">
            <div class="overflow-hidden rounded-[2rem] border border-white/60 bg-white/80 p-8 shadow-[0_30px_80px_rgba(15,23,42,0.12)] backdrop-blur">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-600">PDrive Workspace</p>
                        <h1 class="text-4xl font-semibold tracking-tight text-slate-950">
                            {{ $currentFolder ? $currentFolder->name : 'Your cloud storage, organized.' }}
                        </h1>
                        <p class="max-w-2xl text-sm leading-6 text-slate-600">
                            Upload files, nest folders, favorite what matters, search fast, and recover anything from trash.
                        </p>
                    </div>

                    <form method="GET" action="{{ $currentFolder ? route('folders.show', $currentFolder) : route('dashboard') }}" class="w-full max-w-md">
                        <label for="q" class="sr-only">Search</label>
                        <div class="flex rounded-2xl border border-slate-200 bg-slate-50 p-2">
                            <input
                                id="q"
                                name="q"
                                value="{{ $query }}"
                                placeholder="Search files and folders in this view"
                                class="w-full bg-transparent px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400"
                            >
                            <button class="rounded-xl bg-slate-950 px-4 py-2 text-sm font-medium text-white">Search</button>
                        </div>
                    </form>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-3xl bg-slate-950 p-5 text-white">
                        <p class="text-sm text-slate-300">Storage used</p>
                        <p class="mt-2 text-3xl font-semibold">{{ $stats['storage_used_human'] }}</p>
                        <p class="mt-2 text-xs text-slate-400">of {{ number_format($stats['storage_limit'] / 1024 / 1024 / 1024, 0) }} GB available</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Files</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['files'] }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Folders</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['folders'] }}</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-sm text-slate-500">Favorites</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-950">{{ $stats['favorites'] }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-[radial-gradient(circle_at_top,#e0f2fe,transparent_55%),linear-gradient(135deg,#082f49,#0f172a)] p-6 text-white shadow-[0_30px_80px_rgba(8,47,73,0.25)]">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-200">Quick actions</p>
                <div class="mt-5 space-y-4">
                    <form method="POST" action="{{ route('folders.store') }}" class="rounded-3xl bg-white/10 p-4">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">
                        <label class="text-sm font-medium text-sky-50">Create folder</label>
                        <div class="mt-3 flex gap-2">
                            <input name="name" placeholder="Design assets" class="w-full rounded-2xl border border-white/20 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-sky-100/60 outline-none">
                            <button class="rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-950">Add</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" class="rounded-3xl bg-white/10 p-4">
                        @csrf
                        <input type="hidden" name="folder_id" value="{{ $currentFolder?->id }}">
                        <label class="text-sm font-medium text-sky-50">Upload files</label>
                        <input type="file" name="files[]" multiple class="mt-3 block w-full rounded-2xl border border-dashed border-white/25 bg-white/5 px-4 py-3 text-sm text-white file:mr-3 file:rounded-xl file:border-0 file:bg-white file:px-3 file:py-2 file:text-sm file:font-semibold file:text-slate-950">
                        <button class="mt-3 w-full rounded-2xl bg-sky-300 px-4 py-3 text-sm font-semibold text-slate-950">Upload now</button>
                    </form>
                </div>

                <div class="mt-6 rounded-3xl border border-white/10 bg-black/10 p-4">
                    <p class="text-sm font-medium text-sky-100">Recent uploads</p>
                    <div class="mt-3 space-y-3">
                        @forelse ($recentUploads as $recentFile)
                            <a href="{{ route('files.show', $recentFile) }}" class="flex items-center justify-between rounded-2xl bg-white/5 px-4 py-3 text-sm hover:bg-white/10">
                                <span class="truncate pe-4">{{ $recentFile->original_name }}</span>
                                <span class="shrink-0 text-sky-200">{{ $recentFile->human_size }}</span>
                            </a>
                        @empty
                            <p class="text-sm text-sky-100/70">Uploads will appear here once you add your first files.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-8 rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-[0_20px_60px_rgba(15,23,42,0.08)] backdrop-blur">
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="font-medium text-slate-700 hover:text-slate-950">Home</a>
                @foreach ($breadcrumbs as $breadcrumb)
                    <span>/</span>
                    <a href="{{ route('folders.show', $breadcrumb) }}" class="font-medium text-slate-700 hover:text-slate-950">{{ $breadcrumb->name }}</a>
                @endforeach
            </div>

            <div class="mt-6 grid gap-8 xl:grid-cols-[1fr_1.25fr]">
                <div>
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-950">Folders</h2>
                        <span class="text-sm text-slate-500">{{ $folders->count() }} total</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($folders as $folder)
                            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div>
                                        <a href="{{ route('folders.show', $folder) }}" class="text-base font-semibold text-slate-950 hover:text-sky-700">{{ $folder->name }}</a>
                                        <p class="mt-1 text-xs uppercase tracking-[0.25em] text-slate-400">{{ $folder->updated_at->diffForHumans() }}</p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('folders.favorite', $folder) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="rounded-2xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">{{ $folder->is_favorite ? 'Unfavorite' : 'Favorite' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('folders.destroy', $folder) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2xl border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700">Trash</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 md:grid-cols-3">
                                    <form method="POST" action="{{ route('folders.update', $folder) }}" class="rounded-2xl bg-white p-3">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rename</label>
                                        <input name="name" value="{{ $folder->name }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                        <button class="mt-2 w-full rounded-xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Save</button>
                                    </form>

                                    <form method="POST" action="{{ route('folders.move', $folder) }}" class="rounded-2xl bg-white p-3">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Move</label>
                                        <select name="parent_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                            <option value="">Root</option>
                                            @foreach ($allFolders as $targetFolder)
                                                <option value="{{ $targetFolder->id }}" @selected($folder->parent_id === $targetFolder->id)>{{ $targetFolder->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="mt-2 w-full rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">Move folder</button>
                                    </form>

                                    <form method="POST" action="{{ route('folders.copy', $folder) }}" class="rounded-2xl bg-white p-3">
                                        @csrf
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Copy</label>
                                        <select name="parent_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                            <option value="">Root</option>
                                            @foreach ($allFolders as $targetFolder)
                                                <option value="{{ $targetFolder->id }}">{{ $targetFolder->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="mt-2 w-full rounded-xl bg-sky-100 px-3 py-2 text-xs font-semibold text-sky-800">Copy folder</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500">
                                No folders here yet. Create one from Quick Actions to start nesting content.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-slate-950">Files</h2>
                        <span class="text-sm text-slate-500">{{ $files->count() }} total</span>
                    </div>

                    <div class="mt-4 space-y-3">
                        @forelse ($files as $file)
                            <article class="rounded-3xl border border-slate-200 bg-white p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0">
                                        <a href="{{ route('files.show', $file) }}" class="block truncate text-base font-semibold text-slate-950 hover:text-sky-700">{{ $file->original_name }}</a>
                                        <p class="mt-1 text-sm text-slate-500">{{ $file->human_size }} • {{ $file->mime_type ?: 'Unknown type' }}</p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('files.download', $file) }}" class="rounded-2xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">Download</a>
                                        <form method="POST" action="{{ route('files.favorite', $file) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="rounded-2xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">{{ $file->is_favorite ? 'Unfavorite' : 'Favorite' }}</button>
                                        </form>
                                        <form method="POST" action="{{ route('files.destroy', $file) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2xl border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700">Trash</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 md:grid-cols-3">
                                    <form method="POST" action="{{ route('files.update', $file) }}" class="rounded-2xl bg-slate-50 p-3">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rename</label>
                                        <input name="original_name" value="{{ $file->original_name }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                        <button class="mt-2 w-full rounded-xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Save</button>
                                    </form>

                                    <form method="POST" action="{{ route('files.move', $file) }}" class="rounded-2xl bg-slate-50 p-3">
                                        @csrf
                                        @method('PATCH')
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Move</label>
                                        <select name="folder_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                            <option value="">Root</option>
                                            @foreach ($allFolders as $targetFolder)
                                                <option value="{{ $targetFolder->id }}" @selected($file->folder_id === $targetFolder->id)>{{ $targetFolder->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="mt-2 w-full rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700">Move file</button>
                                    </form>

                                    <form method="POST" action="{{ route('files.copy', $file) }}" class="rounded-2xl bg-slate-50 p-3">
                                        @csrf
                                        <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Copy</label>
                                        <select name="folder_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                            <option value="">Root</option>
                                            @foreach ($allFolders as $targetFolder)
                                                <option value="{{ $targetFolder->id }}">{{ $targetFolder->name }}</option>
                                            @endforeach
                                        </select>
                                        <button class="mt-2 w-full rounded-xl bg-sky-100 px-3 py-2 text-xs font-semibold text-sky-800">Copy file</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500">
                                No files in this location yet. Upload one from the panel above.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
