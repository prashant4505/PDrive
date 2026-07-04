<x-app-layout>
<div>
    {{-- Header --}}
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Favorites</h1>
                <p class="mt-0.5 text-sm text-gray-500">{{ $folders->count() + $files->count() }} pinned item{{ ($folders->count() + $files->count()) === 1 ? '' : 's' }}</p>
            </div>
            <form method="GET" action="{{ route('favorites') }}" class="flex items-center gap-2">
                <div class="flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 focus-within:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-100 transition">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <input name="q" value="{{ $query }}" placeholder="Filter favorites…" class="w-44 bg-transparent text-sm text-gray-900 outline-none placeholder:text-gray-400">
                </div>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">Filter</button>
            </form>
        </div>
    </div>

    <div class="p-6 space-y-8">
        {{-- Favorite Folders --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Folders</h2>
                <span class="text-xs text-gray-400">{{ $folders->count() }}</span>
            </div>
            @if($folders->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-8 text-sm text-gray-400">
                    No favorite folders yet. Use the ⋯ menu on any folder to pin it.
                </div>
            @else
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
                    @foreach($folders as $folder)
                    <div class="group relative flex flex-col items-center gap-2 rounded-xl border border-gray-200 bg-white p-4 transition-all hover:border-amber-200 hover:shadow-md hover:shadow-amber-100/50">
                        <a href="{{ route('folders.show', $folder) }}" class="flex flex-col items-center gap-2">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50">
                                <svg viewBox="0 0 24 24" class="h-7 w-7 text-amber-400 fill-current">
                                    <path d="M19.5 21a3 3 0 0 0 3-3v-4.5a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3V18a3 3 0 0 0 3 3h15ZM1.5 10.146V6a3 3 0 0 1 3-3h5.379a2.25 2.25 0 0 1 1.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 0 1 3 3v1.146A4.483 4.483 0 0 0 19.5 12h-15a4.483 4.483 0 0 0-3 1.146Z"/>
                                </svg>
                            </div>
                            <p class="w-full truncate text-center text-xs font-semibold text-gray-800">{{ $folder->name }}</p>
                        </a>
                        <form method="POST" action="{{ route('folders.favorite', $folder) }}" class="absolute right-1.5 top-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf @method('PATCH')
                            <button title="Remove from favorites" class="flex h-6 w-6 items-center justify-center rounded-lg bg-white text-amber-400 shadow-sm hover:bg-amber-50 hover:text-amber-600">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Favorite Files --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Files</h2>
                <span class="text-xs text-gray-400">{{ $files->count() }}</span>
            </div>
            @if($files->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-8 text-sm text-gray-400">
                    No favorite files yet. Use the ⋯ menu on any file to pin it.
                </div>
            @else
                <div class="space-y-2">
                    @foreach($files as $file)
                    <div class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3 transition-all hover:border-indigo-200 hover:shadow-sm">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg
                            {{ $file->isImage() ? 'bg-sky-50' : ($file->isPdf() ? 'bg-rose-50' : ($file->isVideo() ? 'bg-violet-50' : 'bg-gray-100')) }}">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current
                                {{ $file->isImage() ? 'text-sky-400' : ($file->isPdf() ? 'text-rose-400' : ($file->isVideo() ? 'text-violet-400' : 'text-gray-400')) }}">
                                <path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/>
                            </svg>
                        </div>
                        <a href="{{ route('files.show', $file) }}" class="min-w-0 flex-1 truncate text-sm font-medium text-gray-800 hover:text-indigo-600 transition-colors">{{ $file->original_name }}</a>
                        <span class="shrink-0 text-xs text-gray-400">{{ $file->human_size }}</span>
                        <a href="{{ route('files.download', $file) }}" class="shrink-0 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg border border-gray-200 px-2.5 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50">
                            Download
                        </a>
                        <form method="POST" action="{{ route('files.favorite', $file) }}" class="shrink-0">
                            @csrf @method('PATCH')
                            <button title="Remove from favorites" class="flex h-7 w-7 items-center justify-center rounded-lg text-amber-400 hover:bg-amber-50 hover:text-amber-600 transition-colors">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
