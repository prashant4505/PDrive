<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-[0_20px_60px_rgba(15,23,42,0.08)]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-rose-600">Trash</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-slate-950">Restore or permanently delete</h1>
            </div>

            <div class="mt-8 grid gap-8 lg:grid-cols-2">
                <section>
                    <h2 class="text-lg font-semibold text-slate-950">Folders</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($folders as $folder)
                            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $folder->name }}</p>
                                        <p class="text-sm text-slate-500">Deleted {{ $folder->deleted_at?->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('folders.restore', $folder->id) }}">
                                            @csrf
                                            <button class="rounded-2xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Restore</button>
                                        </form>
                                        <form method="POST" action="{{ route('folders.force-delete', $folder->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2xl border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700">Delete forever</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-8 text-sm text-slate-500">Trash is clear for folders.</div>
                        @endforelse
                    </div>
                </section>

                <section>
                    <h2 class="text-lg font-semibold text-slate-950">Files</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($files as $file)
                            <article class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ $file->original_name }}</p>
                                        <p class="text-sm text-slate-500">Deleted {{ $file->deleted_at?->diffForHumans() }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <form method="POST" action="{{ route('files.restore', $file->id) }}">
                                            @csrf
                                            <button class="rounded-2xl bg-slate-950 px-3 py-2 text-xs font-semibold text-white">Restore</button>
                                        </form>
                                        <form method="POST" action="{{ route('files.force-delete', $file->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2xl border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700">Delete forever</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 px-6 py-8 text-sm text-slate-500">Trash is clear for files.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
