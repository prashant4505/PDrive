<x-app-layout>
    <div
        x-data="{
            viewerOpen: false,
            viewerSrc: '',
            viewerName: '',
            openImage(src, name) {
                this.viewerSrc = src;
                this.viewerName = name;
                this.viewerOpen = true;
                document.body.classList.add('overflow-hidden');
            },
            closeImage() {
                this.viewerOpen = false;
                this.viewerSrc = '';
                this.viewerName = '';
                document.body.classList.remove('overflow-hidden');
            },
        }"
        @keydown.escape.window="closeImage()"
        class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8"
    >
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
                            <button class="bg-slate-950 px-4 py-2 text-sm font-medium text-white">Search</button>
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
                                        <a href="{{ route('folders.show', $folder) }}" class="rounded-2xl border border-sky-200 px-3 py-2 text-xs font-semibold text-sky-700">Open</a>
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

                    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        @forelse ($files as $file)
                            <article
                                x-data="{ menuOpen: false, panel: null }"
                                @click.outside="menuOpen = false"
                                class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-[0_20px_45px_rgba(15,23,42,0.08)]"
                            >
                                <div class="relative aspect-[4/3] border-b border-slate-200 bg-slate-100">
                                    @if ($file->isImage())
                                        <button
                                            type="button"
                                            @click="openImage(@js($file->preview_url), @js($file->original_name))"
                                            class="block h-full w-full"
                                        >
                                            <img
                                                src="{{ $file->preview_url }}"
                                                alt="{{ $file->original_name }}"
                                                class="h-full w-full object-cover"
                                            >
                                        </button>
                                    @elseif ($file->isPdf())
                                        <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-rose-50 via-white to-slate-100 p-6 text-center">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-100 text-rose-600">
                                                <svg viewBox="0 0 24 24" class="h-8 w-8 fill-current" aria-hidden="true">
                                                    <path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Zm6.25 1.8c.16.03.31.11.43.23l3.54 3.54c.12.12.2.27.23.43h-3.2a1 1 0 0 1-1-1v-3.2ZM8 13.25h8a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1 0-1.5Zm0 3h5a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1 0-1.5Z"/>
                                                </svg>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold uppercase tracking-[0.3em] text-rose-500">PDF</p>
                                        </a>
                                    @else
                                        <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br from-slate-50 via-white to-sky-50 p-6 text-center">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-900 text-white">
                                                <svg viewBox="0 0 24 24" class="h-8 w-8 fill-current" aria-hidden="true">
                                                    <path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Zm6.25 1.8c.16.03.31.11.43.23l3.54 3.54c.12.12.2.27.23.43h-3.2a1 1 0 0 1-1-1v-3.2ZM8 13h8a.75.75 0 0 1 0 1.5H8A.75.75 0 0 1 8 13Zm0 3h8a.75.75 0 0 1 0 1.5H8A.75.75 0 0 1 8 16Z"/>
                                                </svg>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">{{ strtoupper($file->extension ?: 'file') }}</p>
                                        </a>
                                    @endif

                                    <div class="absolute right-3 top-3">
                                        <button
                                            type="button"
                                            @click.stop="menuOpen = !menuOpen"
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-sm backdrop-blur hover:bg-white"
                                            aria-label="Open file actions"
                                        >
                                            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                                                <path d="M12 7a1.75 1.75 0 1 0 0-3.5A1.75 1.75 0 0 0 12 7Zm0 6.75a1.75 1.75 0 1 0 0-3.5 1.75 1.75 0 0 0 0 3.5ZM13.75 19a1.75 1.75 0 1 1-3.5 0 1.75 1.75 0 0 1 3.5 0Z"/>
                                            </svg>
                                        </button>

                                        <div
                                            x-cloak
                                            x-show="menuOpen"
                                            x-transition.opacity.scale.origin.top.right
                                            class="absolute right-0 top-12 z-20 w-52 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-[0_20px_45px_rgba(15,23,42,0.14)]"
                                        >
                                            @if ($file->isImage())
                                                <button type="button" @click="openImage(@js($file->preview_url), @js($file->original_name)); menuOpen = false" class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Open</button>
                                            @else
                                                <a href="{{ route('files.show', $file) }}" class="flex rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Open</a>
                                            @endif
                                            <a href="{{ route('files.download', $file) }}" class="flex rounded-xl px-3 py-2 text-sm text-slate-700 hover:bg-slate-100">Download</a>
                                            <button type="button" @click="panel = panel === 'rename' ? null : 'rename'; menuOpen = false" class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Rename</button>
                                            <button type="button" @click="panel = panel === 'move' ? null : 'move'; menuOpen = false" class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Move</button>
                                            <button type="button" @click="panel = panel === 'copy' ? null : 'copy'; menuOpen = false" class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">Copy</button>
                                            <form method="POST" action="{{ route('files.favorite', $file) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-slate-700 hover:bg-slate-100">{{ $file->is_favorite ? 'Remove from favorites' : 'Add to favorites' }}</button>
                                            </form>
                                            <form method="POST" action="{{ route('files.destroy', $file) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="flex w-full rounded-xl px-3 py-2 text-left text-sm text-rose-700 hover:bg-rose-50">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="min-w-0">
                                        @if ($file->isImage())
                                            <button
                                                type="button"
                                                @click="openImage(@js($file->preview_url), @js($file->original_name))"
                                                class="block max-w-full truncate text-left text-base font-semibold text-slate-950 hover:text-sky-700"
                                            >
                                                {{ $file->original_name }}
                                            </button>
                                        @else
                                            <a href="{{ route('files.show', $file) }}" class="block truncate text-base font-semibold text-slate-950 hover:text-sky-700">{{ $file->original_name }}</a>
                                        @endif
                                        <p class="mt-1 text-sm text-slate-500">{{ $file->human_size }}</p>
                                    </div>

                                    <div x-cloak x-show="panel === 'rename'" x-transition class="mt-4 rounded-2xl bg-slate-50 p-3">
                                        <form method="POST" action="{{ route('files.update', $file) }}">
                                            @csrf
                                            @method('PATCH')
                                            <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Rename</label>
                                            <input name="original_name" value="{{ $file->original_name }}" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                            <div class="mt-3 flex gap-2">
                                                <button class="flex-1 rounded-xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Save</button>
                                                <button type="button" @click="panel = null" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div x-cloak x-show="panel === 'move'" x-transition class="mt-4 rounded-2xl bg-slate-50 p-3">
                                        <form method="POST" action="{{ route('files.move', $file) }}">
                                            @csrf
                                            @method('PATCH')
                                            <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Move</label>
                                            <select name="folder_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                                <option value="">Root</option>
                                                @foreach ($allFolders as $targetFolder)
                                                    <option value="{{ $targetFolder->id }}" @selected($file->folder_id === $targetFolder->id)>{{ $targetFolder->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3 flex gap-2">
                                                <button class="flex-1 rounded-xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Move file</button>
                                                <button type="button" @click="panel = null" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div x-cloak x-show="panel === 'copy'" x-transition class="mt-4 rounded-2xl bg-slate-50 p-3">
                                        <form method="POST" action="{{ route('files.copy', $file) }}">
                                            @csrf
                                            <label class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Copy</label>
                                            <select name="folder_id" class="mt-2 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm">
                                                <option value="">Root</option>
                                                @foreach ($allFolders as $targetFolder)
                                                    <option value="{{ $targetFolder->id }}">{{ $targetFolder->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="mt-3 flex gap-2">
                                                <button class="flex-1 rounded-xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Copy file</button>
                                                <button type="button" @click="panel = null" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="sm:col-span-2 2xl:col-span-3 rounded-3xl border border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500">
                                No files in this location yet. Upload one from the panel above.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <div
            x-cloak
            x-show="viewerOpen"
            x-transition.opacity
            class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-950/90 p-4 sm:p-8"
            @click.self="closeImage()"
        >
            <button
                type="button"
                @click="closeImage()"
                class="absolute right-4 top-4 rounded-full bg-white/10 px-4 py-2 text-sm font-semibold text-white backdrop-blur hover:bg-white/20"
            >
                Close
            </button>

            <div class="w-full max-w-6xl">
                <p class="mb-4 truncate text-center text-sm font-medium text-white/80" x-text="viewerName"></p>
                <img
                    :src="viewerSrc"
                    :alt="viewerName"
                    class="max-h-[85vh] w-full rounded-3xl object-contain"
                >
            </div>
        </div>
    </div>
</x-app-layout>
