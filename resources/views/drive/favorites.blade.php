<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-amber-600">Favorites</p>
                    <h1 class="mt-2 text-4xl font-semibold tracking-tight text-slate-950">Pinned for quick access</h1>
                </div>
                <form method="GET" action="{{ route('favorites') }}" class="w-full max-w-md">
                    <input name="q" value="{{ $query }}" placeholder="Filter favorites" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm">
                </form>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <section>
                    <h2 class="text-lg font-semibold text-slate-950">Folders</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($folders as $folder)
                            <a href="{{ route('folders.show', $folder) }}" class="block rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm font-medium text-slate-800 hover:border-sky-300 hover:text-sky-700">
                                {{ $folder->name }}
                            </a>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-8 text-sm text-slate-500">No favorite folders yet.</div>
                        @endforelse
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-semibold text-slate-950">Files</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($files as $file)
                            <a href="{{ route('files.show', $file) }}" class="flex items-center justify-between rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm hover:border-sky-300">
                                <span class="truncate pe-4 font-medium text-slate-800">{{ $file->original_name }}</span>
                                <span class="shrink-0 text-slate-500">{{ $file->human_size }}</span>
                            </a>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-8 text-sm text-slate-500">No favorite files yet.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
