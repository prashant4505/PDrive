<x-app-layout>
<div>
    {{-- Header --}}
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold text-gray-900">Trash</h1>
                <p class="mt-0.5 text-sm text-gray-500">{{ $folders->count() + $files->count() }} item{{ ($folders->count() + $files->count()) === 1 ? '' : 's' }} in trash</p>
            </div>
            @if($folders->count() + $files->count() > 0)
            <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5">
                <svg class="h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                <p class="text-sm font-medium text-red-700">Permanent deletion cannot be undone</p>
            </div>
            @endif
        </div>
    </div>

    <div class="p-6 space-y-8">

        {{-- Trashed Folders --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Folders</h2>
                <span class="text-xs text-gray-400">{{ $folders->count() }}</span>
            </div>
            @if($folders->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-8 text-sm text-gray-400">
                    No folders in trash.
                </div>
            @else
                <div class="space-y-2">
                    @foreach($folders as $folder)
                    <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-red-400 fill-current">
                                <path d="M19.5 21a3 3 0 0 0 3-3v-4.5a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3V18a3 3 0 0 0 3 3h15ZM1.5 10.146V6a3 3 0 0 1 3-3h5.379a2.25 2.25 0 0 1 1.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 0 1 3 3v1.146A4.483 4.483 0 0 0 19.5 12h-15a4.483 4.483 0 0 0-3 1.146Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-800">{{ $folder->name }}</p>
                            <p class="text-xs text-gray-400">Deleted {{ $folder->deleted_at?->diffForHumans() }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <form method="POST" action="{{ route('folders.restore', $folder->id) }}">
                                @csrf
                                <button class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('folders.force-delete', $folder->id) }}"
                                  onsubmit="return confirm('Permanently delete &quot;{{ addslashes($folder->name) }}&quot;? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors">Delete forever</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Trashed Files --}}
        <div>
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-xs font-semibold uppercase tracking-widest text-gray-400">Files</h2>
                <span class="text-xs text-gray-400">{{ $files->count() }}</span>
            </div>
            @if($files->isEmpty())
                <div class="flex items-center justify-center rounded-xl border border-dashed border-gray-200 py-8 text-sm text-gray-400">
                    No files in trash.
                </div>
            @else
                <div class="space-y-2">
                    @foreach($files as $file)
                    <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white px-4 py-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-red-50">
                            <svg viewBox="0 0 24 24" class="h-4 w-4 text-red-400 fill-current">
                                <path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-gray-800">{{ $file->original_name }}</p>
                            <p class="text-xs text-gray-400">Deleted {{ $file->deleted_at?->diffForHumans() }} &middot; {{ $file->human_size }}</p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <form method="POST" action="{{ route('files.restore', $file->id) }}">
                                @csrf
                                <button class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('files.force-delete', $file->id) }}"
                                  onsubmit="return confirm('Permanently delete &quot;{{ addslashes($file->original_name) }}&quot;? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100 transition-colors">Delete forever</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
