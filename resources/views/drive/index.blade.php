<x-app-layout>
@php
    $imageFiles = $files->filter(fn($f) => $f->isImage())->values();
    $galleryImages = $imageFiles->map(fn($f) => ['src' => $f->preview_url, 'name' => $f->original_name])->values()->all();
    $imageIndexMap = $imageFiles->pluck('id')->flip();
@endphp
<div
    x-data="{ actionPanel: null }"
    x-init="$store.viewer.images = @js($galleryImages)"
    @keydown.escape.window="$store.viewer.open ? $store.viewer.close() : (actionPanel = null)"
    @keydown.arrow-left.window="$store.viewer.open && $store.viewer.prev()"
    @keydown.arrow-right.window="$store.viewer.open && $store.viewer.next()"
>

    {{-- Page header --}}
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex flex-wrap items-center gap-3">

            {{-- Breadcrumb --}}
            <div class="flex min-w-0 flex-1 items-center gap-1.5 text-sm">
                <a href="{{ route('dashboard') }}" class="shrink-0 font-semibold text-gray-900 hover:text-indigo-600 transition-colors">My Drive</a>
                @foreach ($breadcrumbs as $crumb)
                    <svg class="h-4 w-4 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    <a href="{{ route('folders.show', $crumb) }}" class="shrink-0 font-medium text-gray-600 hover:text-indigo-600 transition-colors">{{ $crumb->name }}</a>
                @endforeach
                @if($currentFolder)
                    <svg class="h-4 w-4 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                    <span class="truncate font-semibold text-gray-900">{{ $currentFolder->name }}</span>
                @endif
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ $currentFolder ? route('folders.show', $currentFolder) : route('dashboard') }}" class="flex items-center gap-2">
                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100 transition">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <input name="q" value="{{ $query }}" placeholder="Search…" class="w-44 bg-transparent text-sm text-gray-900 outline-none placeholder:text-gray-400">
                </div>
            </form>

            {{-- Action buttons --}}
            <div class="flex items-center gap-2">
                <button
                    @click="actionPanel = actionPanel === 'upload' ? null : 'upload'"
                    :class="actionPanel === 'upload' ? 'border-indigo-300 bg-indigo-50 text-indigo-700' : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50'"
                    class="flex items-center gap-1.5 rounded-xl border px-3 py-2 text-sm font-medium transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" /></svg>
                    Upload
                </button>
                <button
                    @click="actionPanel = actionPanel === 'folder' ? null : 'folder'"
                    :class="actionPanel === 'folder' ? 'bg-indigo-700' : 'bg-indigo-600 hover:bg-indigo-500'"
                    class="flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-semibold text-white shadow-sm transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New folder
                </button>
            </div>
        </div>

        {{-- Stats bar --}}
        <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-gray-400">
            <span><span class="font-semibold text-gray-700">{{ $stats['files'] }}</span> files</span>
            <span>&middot;</span>
            <span><span class="font-semibold text-gray-700">{{ $stats['folders'] }}</span> folders</span>
            <span>&middot;</span>
            <span><span class="font-semibold text-gray-700">{{ $stats['storage_used_human'] }}</span> used</span>
            <span>&middot;</span>
            <span><span class="font-semibold text-gray-700">{{ $stats['favorites'] }}</span> favorites</span>
        </div>
    </div>

    {{-- Action panels --}}
    <div x-show="actionPanel === 'folder'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="border-b border-indigo-100 bg-indigo-50 px-6 py-4">
        <form method="POST" action="{{ route('folders.store') }}" class="flex items-center gap-3">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">
            <div class="flex items-center gap-2 rounded-xl border border-indigo-200 bg-white px-3 py-2 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100">
                <svg class="h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v8.25A2.25 2.25 0 0 0 4.5 16.5h15a2.25 2.25 0 0 0 2.25-2.25V8.25A2.25 2.25 0 0 0 19.5 6h-5.379a1.5 1.5 0 0 1-1.06-.44Z" /></svg>
                <input name="name" placeholder="Folder name…" autofocus class="w-52 bg-transparent text-sm text-gray-900 outline-none placeholder:text-gray-400">
            </div>
            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">Create</button>
            <button type="button" @click="actionPanel = null" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">Cancel</button>
        </form>
    </div>

    <div
        x-show="actionPanel === 'upload'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-data="uploadQueue(@js(route('files.store')), @js($currentFolder?->id), @js($maxUploadBytes))"
        class="border-b border-indigo-100 bg-indigo-50 px-6 py-4"
    >
        <div class="flex flex-wrap items-center gap-3">
            <input
                type="file" multiple :disabled="uploading"
                @change="addFiles($event.target.files); $event.target.value = ''"
                class="block rounded-xl border border-indigo-200 bg-white px-3 py-2 text-sm text-gray-700 disabled:opacity-50
                       file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-600 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-white hover:file:bg-indigo-500"
            >
            <button type="button" @click="startUpload()" :disabled="uploading || ! hasPending" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:cursor-not-allowed disabled:opacity-50 transition-colors">
                <span x-show="! uploading">Upload now</span>
                <span x-show="uploading" x-cloak>Uploading… <span x-text="overallProgress"></span>%</span>
            </button>
            <button type="button" @click="reset(); actionPanel = null" :disabled="uploading" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 disabled:opacity-50 transition-colors">Cancel</button>
        </div>

        {{-- Upload queue --}}
        <div x-show="queue.length > 0" x-cloak class="mt-3 space-y-2">
            <template x-for="item in queue" :key="item.name + item.size">
                <div class="flex items-center gap-3 rounded-xl border border-indigo-200 bg-white px-3 py-2">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="truncate text-xs font-semibold text-gray-800" x-text="item.name"></span>
                            <span class="shrink-0 text-[11px] text-gray-400" x-text="formatSize(item.size)"></span>
                        </div>
                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                            <div
                                class="h-full rounded-full transition-all"
                                :class="item.status === 'error' ? 'bg-rose-400' : (item.status === 'done' ? 'bg-emerald-500' : 'bg-indigo-500')"
                                :style="`width: ${item.status === 'error' ? 100 : item.progress}%`"
                            ></div>
                        </div>
                        <p class="mt-1 text-[11px]" :class="item.status === 'error' ? 'text-rose-500' : 'text-gray-400'">
                            <span x-show="item.status === 'pending'">Waiting…</span>
                            <span x-show="item.status === 'uploading'">Uploading — <span x-text="item.progress"></span>%</span>
                            <span x-show="item.status === 'done'">Uploaded</span>
                            <span x-show="item.status === 'error'" x-text="item.error"></span>
                        </p>
                    </div>
                    <button type="button" x-show="item.status === 'error'" @click="retry(item)" class="shrink-0 rounded-lg border border-rose-200 px-2.5 py-1 text-[11px] font-semibold text-rose-600 hover:bg-rose-50">Retry</button>
                    <button type="button" x-show="item.status !== 'uploading'" @click="removeFile(item)" class="shrink-0 rounded-lg border border-gray-200 px-2.5 py-1 text-[11px] font-semibold text-gray-500 hover:bg-gray-50">✕</button>
                </div>
            </template>
        </div>
    </div>

    {{-- Content area --}}
    <div class="p-6 space-y-8">

        {{-- Recent uploads strip (root only) --}}
        @if(!$currentFolder && $recentUploads->isNotEmpty())
        <div>
            <h2 class="mb-3 text-xs font-semibold uppercase tracking-widest text-gray-400">Recent uploads</h2>
            <div class="flex gap-3 overflow-x-auto pb-1">
                @foreach($recentUploads as $rf)
                <a href="{{ route('files.show', $rf) }}" class="flex shrink-0 items-center gap-2.5 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                    <div class="h-6 w-6 shrink-0 rounded-lg {{ $rf->isImage() ? 'bg-sky-100' : ($rf->isPdf() ? 'bg-rose-100' : 'bg-gray-100') }} flex items-center justify-center">
                        <svg class="h-3 w-3 {{ $rf->isImage() ? 'text-sky-500' : ($rf->isPdf() ? 'text-rose-500' : 'text-gray-500') }} fill-current" viewBox="0 0 24 24"><path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/></svg>
                    </div>
                    <span class="max-w-[140px] truncate font-medium text-gray-800">{{ $rf->original_name }}</span>
                    <span class="shrink-0 text-xs text-gray-400">{{ $rf->human_size }}</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Folders --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Folders</h2>
                @if($folders->isNotEmpty())
                    <span class="text-xs text-gray-400">{{ $folders->count() }}</span>
                @endif
            </div>

            @if($folders->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-10 text-sm text-gray-400">
                    No folders yet — click <strong class="mx-1 text-gray-600">New folder</strong> to create one.
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    @foreach ($folders as $folder)
                    <article
                        x-data="{ menuOpen: false, panel: null }"
                        @click.outside="menuOpen = false"
                        :class="menuOpen ? 'z-30' : 'z-0'"
                        class="group relative overflow-visible"
                    >
                        <a
                            href="{{ route('folders.show', $folder) }}"
                            class="flex flex-col items-center gap-2 rounded-xl border border-gray-200 bg-white p-4 transition-all hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-100 active:scale-95"
                        >
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                                <svg viewBox="0 0 24 24" class="h-7 w-7 text-amber-400 fill-current">
                                    <path d="M19.5 21a3 3 0 0 0 3-3v-4.5a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3V18a3 3 0 0 0 3 3h15ZM1.5 10.146V6a3 3 0 0 1 3-3h5.379a2.25 2.25 0 0 1 1.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 0 1 3 3v1.146A4.483 4.483 0 0 0 19.5 12h-15a4.483 4.483 0 0 0-3 1.146Z"/>
                                </svg>
                            </div>
                            <p class="w-full truncate text-center text-xs font-semibold text-gray-800">{{ $folder->name }}</p>
                            <p class="text-[11px] text-gray-400">
                                {{ $folder->children_count + $folder->files_count }} item{{ ($folder->children_count + $folder->files_count) === 1 ? '' : 's' }}
                            </p>
                        </a>

                        {{-- 3-dot menu --}}
                        <div class="absolute right-1.5 top-1.5">
                            <button
                                type="button"
                                @click.prevent.stop="menuOpen = !menuOpen"
                                :class="menuOpen ? 'bg-gray-100 text-gray-900 ring-gray-300' : 'bg-white text-gray-600 ring-gray-200 hover:bg-gray-100 hover:text-gray-900'"
                                class="flex h-7 w-7 items-center justify-center rounded-lg shadow-sm ring-1 transition-colors"
                                aria-label="Folder actions"
                            >
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current"><path d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z"/></svg>
                            </button>

                            <div
                                x-show="menuOpen"
                                x-cloak
                                x-transition.opacity.scale.origin.top.right
                                class="absolute right-0 top-9 z-40 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white p-1.5 shadow-xl shadow-gray-200/80"
                            >
                                <a href="{{ route('folders.show', $folder) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                    Open
                                </a>
                                <button type="button" @click="panel = panel === 'rename' ? null : 'rename'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                    Rename
                                </button>
                                <button type="button" @click="panel = panel === 'move' ? null : 'move'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                                    Move
                                </button>
                                <button type="button" @click="panel = panel === 'copy' ? null : 'copy'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" /></svg>
                                    Copy
                                </button>
                                <form method="POST" action="{{ route('folders.favorite', $folder) }}">
                                    @csrf @method('PATCH')
                                    <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="h-3.5 w-3.5 {{ $folder->is_favorite ? 'text-amber-400 fill-amber-400' : 'text-gray-400' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" fill="none"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                                        {{ $folder->is_favorite ? 'Remove favorite' : 'Add to favorites' }}
                                    </button>
                                </form>
                                <div class="my-1 h-px bg-gray-100"></div>
                                <form method="POST" action="{{ route('folders.destroy', $folder) }}">
                                    @csrf @method('DELETE')
                                    <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        Move to trash
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Inline action panels --}}
                        @if(!$loop->last || true)
                        <div x-show="panel === 'rename'" x-cloak x-transition class="absolute left-0 right-0 top-full z-30 mt-1 rounded-xl border border-gray-200 bg-white p-3 shadow-xl">
                            <form method="POST" action="{{ route('folders.update', $folder) }}">
                                @csrf @method('PATCH')
                                <p class="mb-2 text-xs font-semibold text-gray-500">Rename folder</p>
                                <input name="name" value="{{ $folder->name }}" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-gray-900 py-2 text-xs font-semibold text-white hover:bg-gray-700">Save</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                        <div x-show="panel === 'move'" x-cloak x-transition class="absolute left-0 right-0 top-full z-30 mt-1 rounded-xl border border-gray-200 bg-white p-3 shadow-xl">
                            <form method="POST" action="{{ route('folders.move', $folder) }}">
                                @csrf @method('PATCH')
                                <p class="mb-2 text-xs font-semibold text-gray-500">Move to</p>
                                <select name="parent_id" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none">
                                    <option value="">Root</option>
                                    @foreach ($allFolders as $tf)
                                        <option value="{{ $tf->id }}" @selected($folder->parent_id === $tf->id)>{{ $tf->name }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-gray-900 py-2 text-xs font-semibold text-white hover:bg-gray-700">Move</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                        <div x-show="panel === 'copy'" x-cloak x-transition class="absolute left-0 right-0 top-full z-30 mt-1 rounded-xl border border-gray-200 bg-white p-3 shadow-xl">
                            <form method="POST" action="{{ route('folders.copy', $folder) }}">
                                @csrf
                                <p class="mb-2 text-xs font-semibold text-gray-500">Copy to</p>
                                <select name="parent_id" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none">
                                    <option value="">Root</option>
                                    @foreach ($allFolders as $tf)
                                        <option value="{{ $tf->id }}">{{ $tf->name }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-indigo-600 py-2 text-xs font-semibold text-white hover:bg-indigo-500">Copy</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </article>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Files --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Files</h2>
                @if($files->isNotEmpty())
                    <span class="text-xs text-gray-400">{{ $files->count() }}</span>
                @endif
            </div>

            @if($files->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-10 text-sm text-gray-400">
                    No files yet — click <strong class="mx-1 text-gray-600">Upload</strong> to add some.
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    @foreach ($files as $file)
                    <article
                        x-data="{ menuOpen: false, panel: null }"
                        @click.outside="menuOpen = false"
                        :class="menuOpen ? 'z-30' : 'z-0'"
                        class="group relative overflow-visible rounded-xl border border-gray-200 bg-white transition-all hover:border-indigo-200 hover:shadow-md hover:shadow-indigo-100"
                    >
                        {{-- Thumbnail / icon --}}
                        @if ($file->isImage())
                            @php $galleryIndex = $imageIndexMap[$file->id] ?? 0; @endphp
                        @endif
                        <div class="aspect-[4/3] overflow-hidden rounded-t-xl border-b border-gray-100 bg-gray-50">
                            @if ($file->isImage())
                                <button type="button" @click="$store.viewer.show({{ $galleryIndex }})" class="block h-full w-full cursor-zoom-in">
                                    <img src="{{ $file->preview_url }}" alt="{{ $file->original_name }}" class="h-full w-full object-cover transition-transform hover:scale-105">
                                </button>
                            @elseif ($file->isPdf())
                                <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center gap-2 bg-rose-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-100">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-rose-500"><path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-rose-400">PDF</span>
                                </a>
                            @elseif ($file->isVideo())
                                <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center gap-2 bg-violet-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-100">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-violet-500"><path d="M4.5 4.5a3 3 0 0 0-3 3v9a3 3 0 0 0 3 3h8.25a3 3 0 0 0 3-3v-9a3 3 0 0 0-3-3H4.5ZM19.94 18.75l-2.69-2.69V7.94l2.69-2.69c.944-.945 2.56-.276 2.56 1.06v11.38c0 1.336-1.616 2.005-2.56 1.06Z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-violet-400">VIDEO</span>
                                </a>
                            @elseif ($file->isAudio())
                                <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center gap-2 bg-green-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-green-500"><path d="M19.952 1.651a.75.75 0 0 1 .298.599V16.303a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.403-4.909l2.311-.66a1.5 1.5 0 0 0 1.088-1.442V6.994l-9 2.572v9.737a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.402-4.909l2.31-.66a1.5 1.5 0 0 0 1.088-1.442V5.25a.75.75 0 0 1 .544-.721l10.5-3a.75.75 0 0 1 .658.122Z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-green-400">AUDIO</span>
                                </a>
                            @else
                                <a href="{{ route('files.show', $file) }}" class="flex h-full w-full flex-col items-center justify-center gap-2 bg-gray-50">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-200">
                                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-gray-500"><path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/></svg>
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ strtoupper($file->extension ?: 'FILE') }}</span>
                                </a>
                            @endif
                        </div>

                        {{-- File info --}}
                        <div class="p-3">
                            @if ($file->isImage())
                                <button type="button" @click="$store.viewer.show({{ $galleryIndex }})" class="block w-full truncate text-left text-xs font-semibold text-gray-800 hover:text-indigo-600">{{ $file->original_name }}</button>
                            @else
                                <a href="{{ route('files.show', $file) }}" class="block truncate text-xs font-semibold text-gray-800 hover:text-indigo-600">{{ $file->original_name }}</a>
                            @endif
                            <p class="mt-0.5 text-[11px] text-gray-400">{{ $file->human_size }}</p>
                        </div>

                        {{-- 3-dot menu --}}
                        <div class="absolute right-1.5 top-1.5">
                            <button
                                type="button"
                                @click.prevent.stop="menuOpen = !menuOpen"
                                :class="menuOpen ? 'bg-gray-900 text-white' : 'bg-white/95 text-gray-700 ring-1 ring-black/10 hover:bg-white hover:text-gray-900'"
                                class="flex h-7 w-7 items-center justify-center rounded-lg shadow-md transition-colors"
                            >
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current"><path d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z"/></svg>
                            </button>

                            <div
                                x-show="menuOpen"
                                x-cloak
                                x-transition.opacity.scale.origin.top.right
                                class="absolute right-0 top-9 z-40 w-48 overflow-hidden rounded-xl border border-gray-200 bg-white p-1.5 shadow-xl shadow-gray-200/80"
                            >
                                @if ($file->isImage())
                                    <button type="button" @click="$store.viewer.show({{ $galleryIndex }}); menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Open</button>
                                @else
                                    <a href="{{ route('files.show', $file) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Open</a>
                                @endif
                                <a href="{{ route('files.download', $file) }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Download</a>
                                <button type="button" @click="panel = panel === 'rename' ? null : 'rename'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Rename</button>
                                <button type="button" @click="panel = panel === 'move' ? null : 'move'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Move</button>
                                <button type="button" @click="panel = panel === 'copy' ? null : 'copy'; menuOpen = false" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Copy</button>
                                <form method="POST" action="{{ route('files.favorite', $file) }}">
                                    @csrf @method('PATCH')
                                    <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ $file->is_favorite ? 'Remove favorite' : 'Add to favorites' }}</button>
                                </form>
                                <div class="my-1 h-px bg-gray-100"></div>
                                <form method="POST" action="{{ route('files.destroy', $file) }}">
                                    @csrf @method('DELETE')
                                    <button class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 hover:bg-red-50">Delete</button>
                                </form>
                            </div>
                        </div>

                        {{-- Inline panels (rename/move/copy) --}}
                        <div x-show="panel === 'rename'" x-cloak x-transition class="border-t border-gray-100 p-3">
                            <form method="POST" action="{{ route('files.update', $file) }}">
                                @csrf @method('PATCH')
                                <input name="original_name" value="{{ $file->original_name }}" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs focus:border-indigo-400 focus:outline-none">
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-gray-900 py-2 text-xs font-semibold text-white">Save</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                        <div x-show="panel === 'move'" x-cloak x-transition class="border-t border-gray-100 p-3">
                            <form method="POST" action="{{ route('files.move', $file) }}">
                                @csrf @method('PATCH')
                                <select name="folder_id" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs focus:border-indigo-400 focus:outline-none">
                                    <option value="">Root</option>
                                    @foreach ($allFolders as $tf)<option value="{{ $tf->id }}" @selected($file->folder_id === $tf->id)>{{ $tf->name }}</option>@endforeach
                                </select>
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-gray-900 py-2 text-xs font-semibold text-white">Move</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                        <div x-show="panel === 'copy'" x-cloak x-transition class="border-t border-gray-100 p-3">
                            <form method="POST" action="{{ route('files.copy', $file) }}">
                                @csrf
                                <select name="folder_id" class="block w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs focus:border-indigo-400 focus:outline-none">
                                    <option value="">Root</option>
                                    @foreach ($allFolders as $tf)<option value="{{ $tf->id }}">{{ $tf->name }}</option>@endforeach
                                </select>
                                <div class="mt-2 flex gap-2">
                                    <button class="flex-1 rounded-lg bg-indigo-600 py-2 text-xs font-semibold text-white">Copy</button>
                                    <button type="button" @click="panel = null" class="rounded-lg border border-gray-200 px-3 py-2 text-xs text-gray-600 hover:bg-gray-50">✕</button>
                                </div>
                            </form>
                        </div>
                    </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Image viewer --}}
<div
    x-show="$store.viewer.open"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-[80] flex items-center justify-center bg-black/92 select-none"
    @click.self="$store.viewer.close()"
    @touchstart.passive="$store.viewer.touchStartX = $event.touches[0].clientX"
    @touchend.passive="(() => { const dx = $event.changedTouches[0].clientX - $store.viewer.touchStartX; if (Math.abs(dx) > 50) { dx < 0 ? $store.viewer.next() : $store.viewer.prev(); } })()"
>
    {{-- Top bar --}}
    <div class="pointer-events-none absolute inset-x-0 top-0 flex items-center justify-between bg-gradient-to-b from-black/70 to-transparent px-4 py-4">
        <p class="max-w-xs truncate text-sm font-medium text-white/80" x-text="$store.viewer.current.name"></p>
        <div class="pointer-events-auto flex items-center gap-3">
            <span class="text-xs text-white/50" x-text="`${$store.viewer.index + 1} / ${$store.viewer.images.length}`"></span>
            <button @click="$store.viewer.close()" class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white transition-colors hover:bg-white/25">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>

    {{-- Prev --}}
    <button
        x-show="$store.viewer.index > 0"
        @click.stop="$store.viewer.prev()"
        class="absolute left-3 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/25"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
    </button>

    {{-- Image --}}
    <div class="flex max-h-screen w-full max-w-5xl items-center justify-center px-16 py-16" @click.stop>
        <img
            :src="$store.viewer.current.src"
            :alt="$store.viewer.current.name"
            class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl"
        >
    </div>

    {{-- Next --}}
    <button
        x-show="$store.viewer.index < $store.viewer.images.length - 1"
        @click.stop="$store.viewer.next()"
        class="absolute right-3 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/25"
    >
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
    </button>

    {{-- Dot indicators --}}
    <div x-show="$store.viewer.images.length > 1" class="absolute bottom-5 flex items-center gap-1.5">
        <template x-for="(img, i) in $store.viewer.images" :key="i">
            <button
                @click.stop="$store.viewer.index = i"
                :class="i === $store.viewer.index ? 'bg-white w-5' : 'bg-white/40 w-2 hover:bg-white/70'"
                class="h-2 rounded-full transition-all duration-200"
            ></button>
        </template>
    </div>
</div>

</x-app-layout>
