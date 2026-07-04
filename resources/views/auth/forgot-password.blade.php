<x-guest-layout>
    <div class="mb-7">
        <h2 class="text-2xl font-bold tracking-tight text-gray-900">Reset password</h2>
        <p class="mt-1.5 text-sm text-gray-500">Enter your email and we'll send a reset link.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email address')" />
            <x-text-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <button type="submit" class="flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
            Send reset link
        </button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Back to sign in</a>
        </p>
    </form>
</x-guest-layout>
