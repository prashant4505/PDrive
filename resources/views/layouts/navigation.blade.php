@php $route = request()->route()?->getName(); @endphp

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 flex w-60 flex-col bg-gray-950 transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0"
>
    {{-- Logo --}}
    <div class="flex h-14 shrink-0 items-center gap-2.5 border-b border-white/[0.06] px-5">
        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-500 shadow-lg shadow-indigo-500/40">
            <svg viewBox="0 0 24 24" class="h-4 w-4 fill-white">
                <path d="M19.35 10.04A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.04A5.994 5.994 0 0 0 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96ZM14 13v4h-4v-4H7l5-5 5 5h-3Z"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-bold tracking-tight text-white">PDrive</p>
            <p class="text-[10px] text-gray-500">Personal storage</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">
        <p class="mb-2 mt-1 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-600">Workspace</p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                  {{ in_array($route, ['dashboard', 'folders.show']) ? 'bg-indigo-500/15 text-indigo-300' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}"
        >
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v8.25A2.25 2.25 0 0 0 4.5 16.5h15a2.25 2.25 0 0 0 2.25-2.25V8.25A2.25 2.25 0 0 0 19.5 6h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            My Drive
        </a>

        <a href="{{ route('favorites') }}"
           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                  {{ $route === 'favorites' ? 'bg-indigo-500/15 text-indigo-300' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}"
        >
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
            </svg>
            Favorites
        </a>

        <a href="{{ route('trash') }}"
           class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                  {{ $route === 'trash' ? 'bg-indigo-500/15 text-indigo-300' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}"
        >
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
            </svg>
            Trash
        </a>

        <div class="pt-4">
            <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-600">Account</p>
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ $route === 'profile.edit' ? 'bg-indigo-500/15 text-indigo-300' : 'text-gray-400 hover:bg-white/[0.05] hover:text-gray-200' }}"
            >
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                Profile
            </a>
        </div>
    </nav>

    {{-- Storage indicator (shown when stats are available) --}}
    @if(isset($stats))
    @php $pct = $stats['storage_limit'] > 0 ? min(100, round($stats['storage_used'] / $stats['storage_limit'] * 100)) : 0; @endphp
    <div class="border-t border-white/[0.06] px-5 py-4">
        <div class="flex items-center justify-between text-xs">
            <span class="font-medium text-gray-500">Storage</span>
            <span class="text-gray-400">{{ $stats['storage_used_human'] }} / {{ number_format($stats['storage_limit'] / 1073741824, 0) }} GB</span>
        </div>
        <div class="mt-2 h-1 overflow-hidden rounded-full bg-white/10">
            <div class="h-full rounded-full {{ $pct > 85 ? 'bg-red-500' : 'bg-indigo-500' }} transition-all duration-500" style="width: {{ $pct }}%"></div>
        </div>
    </div>
    @else
    {{-- Fallback user info at bottom --}}
    <div class="border-t border-white/[0.06] px-5 py-4">
        <div class="flex items-center gap-2.5">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold uppercase text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
            <div class="min-w-0">
                <p class="truncate text-xs font-semibold text-gray-300">{{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
    @endif
</aside>
