<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600">File preview</p>
                    <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">{{ $file->original_name }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $file->human_size }} • {{ $file->mime_type ?: 'Unknown type' }}</p>
                </div>
                <a href="{{ route('files.download', $file) }}" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white">Download</a>
            </div>

            <div class="mt-8 rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4">
                @if ($file->isImage())
                    <img src="{{ $file->preview_url }}" alt="{{ $file->original_name }}" class="mx-auto max-h-[70vh] rounded-2xl">
                @elseif ($file->isVideo())
                    <video controls class="w-full rounded-2xl">
                        <source src="{{ $file->preview_url }}" type="{{ $file->mime_type }}">
                    </video>
                @elseif ($file->isAudio())
                    <audio controls class="w-full">
                        <source src="{{ $file->preview_url }}" type="{{ $file->mime_type }}">
                    </audio>
                @elseif ($file->isPdf())
                    <iframe src="{{ $file->preview_url }}" class="h-[75vh] w-full rounded-2xl bg-white"></iframe>
                @elseif ($file->isText())
                    <pre class="overflow-x-auto rounded-2xl bg-slate-950 p-6 text-sm leading-7 text-slate-100">{{ $previewText }}</pre>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center text-sm text-slate-500">
                        This file type does not have an inline preview yet. Download it to inspect locally.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
