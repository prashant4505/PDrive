<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <div>
        <x-input-label for="avatar" :value="__('Profile picture')" />
        <div class="mt-2 flex items-center gap-4">
            <img
                x-ref="avatarPreview"
                src="{{ $user->avatarUrl() ?? 'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64"><rect width="64" height="64" rx="32" fill="#e5e7eb"/><text x="32" y="41" text-anchor="middle" font-family="sans-serif" font-size="24" fill="#6b7280">'.strtoupper(Str::substr($user->name, 0, 1)).'</text></svg>') }}"
                alt="Profile picture"
                class="h-16 w-16 rounded-full object-cover border border-gray-200"
            >
            <div>
                <input
                    id="avatar"
                    name="avatar"
                    type="file"
                    accept="image/*"
                    class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-100 file:px-3 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                    x-on:change="const file = $event.target.files[0]; if (file) { $refs.avatarPreview.src = URL.createObjectURL(file); }"
                >
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>
    </div>

    <div>
        <x-input-label for="name" :value="__('Full name')" />
        <x-text-input id="name" name="name" type="text" class="mt-2 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>

    <div>
        <x-input-label for="email" :value="__('Email address')" />
        <x-text-input id="email" name="email" type="email" class="mt-2 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
        <x-input-error class="mt-2" :messages="$errors->get('email')" />

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3">
                <p class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Your email is unverified.
                    <button form="send-verification" class="font-semibold underline hover:no-underline">
                        Resend verification email.
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-green-600">A new verification link has been sent.</p>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center gap-4 pt-1">
        <x-primary-button>{{ __('Save changes') }}</x-primary-button>

        @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-medium text-green-600">Saved.</p>
        @endif
    </div>
</form>
