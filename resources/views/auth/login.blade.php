<x-guest-layout>
    <div class="mb-8">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-700">Welcome back</p>
        <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">Log in to your drive</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Use your account to access files, folders, favorites, recent uploads, and profile settings.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-200 bg-slate-50" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-sky-700 shadow-sm focus:ring-sky-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex flex-col gap-4">
            <x-primary-button class="w-full justify-center rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold">
                {{ __('Log in') }}
            </x-primary-button>

            <div class="flex items-center justify-between gap-4 text-sm">
                <a class="font-medium text-sky-700 hover:text-sky-900" href="{{ route('register') }}">
                    {{ __('Create account') }}
                </a>

                @if (Route::has('password.request'))
                    <a class="text-slate-500 hover:text-slate-800" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>
    </form>
</x-guest-layout>
