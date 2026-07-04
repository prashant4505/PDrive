<x-app-layout>
<div>
    {{-- Header --}}
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ $file->folder ? route('folders.show', $file->folder) : route('dashboard') }}"
                   class="flex items-center gap-1.5 text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    {{ $file->folder ? $file->folder->name : 'My Drive' }}
                </a>
                <svg class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                <div>
                    <h1 class="max-w-sm truncate text-sm font-bold text-gray-900">{{ $file->original_name }}</h1>
                    <p class="text-xs text-gray-400">{{ $file->human_size }} &middot; {{ $file->mime_type ?: 'Unknown type' }}</p>
                </div>
            </div>
            <a href="{{ route('files.download', $file) }}"
               class="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-colors"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download
            </a>
        </div>
    </div>

    {{-- Preview --}}
    <div class="p-6">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            @if ($file->isImage())
                <div class="flex items-center justify-center bg-gray-50 p-6">
                    <img src="{{ $file->preview_url }}" alt="{{ $file->original_name }}" class="max-h-[75vh] rounded-lg object-contain shadow-lg">
                </div>
            @elseif ($file->isVideo())
                <div class="bg-black">
                    <video controls class="mx-auto max-h-[75vh] w-full">
                        <source src="{{ $file->preview_url }}" type="{{ $file->mime_type }}">
                    </video>
                </div>
            @elseif ($file->isAudio())
                <div class="flex items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 p-16">
                    <div class="w-full max-w-lg">
                        <div class="mb-6 flex items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-100">
                                <svg viewBox="0 0 24 24" class="h-8 w-8 fill-indigo-500"><path d="M19.952 1.651a.75.75 0 0 1 .298.599V16.303a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.403-4.909l2.311-.66a1.5 1.5 0 0 0 1.088-1.442V6.994l-9 2.572v9.737a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.402-4.909l2.31-.66a1.5 1.5 0 0 0 1.088-1.442V5.25a.75.75 0 0 1 .544-.721l10.5-3a.75.75 0 0 1 .658.122Z"/></svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $file->original_name }}</p>
                                <p class="text-sm text-gray-500">{{ $file->human_size }}</p>
                            </div>
                        </div>
                        <audio controls class="w-full rounded-xl">
                            <source src="{{ $file->preview_url }}" type="{{ $file->mime_type }}">
                        </audio>
                    </div>
                </div>
            @elseif ($file->isPdf())
                <iframe src="{{ $file->preview_url }}" class="h-[78vh] w-full bg-white"></iframe>
            @elseif ($file->isText())
                <div class="bg-gray-950 p-1">
                    <pre class="overflow-x-auto p-5 text-sm leading-7 text-gray-100">{{ $previewText }}</pre>
                </div>
            @else
                <div class="flex flex-col items-center justify-center gap-4 py-20 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100">
                        <svg viewBox="0 0 24 24" class="h-8 w-8 fill-gray-400"><path d="M7 2.75A2.25 2.25 0 0 0 4.75 5v14A2.25 2.25 0 0 0 7 21.25h10A2.25 2.25 0 0 0 19.25 19V8.56a2.25 2.25 0 0 0-.66-1.59l-3.56-3.56a2.25 2.25 0 0 0-1.59-.66H7Z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700">No preview available</p>
                        <p class="mt-1 text-sm text-gray-400">Download the file to open it locally.</p>
                    </div>
                    <a href="{{ route('files.download', $file) }}" class="mt-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">Download file</a>
                </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
