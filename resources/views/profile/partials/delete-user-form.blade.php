<p class="text-sm text-rose-700/80">
    Once deleted, all your files, folders, and data are permanently removed and cannot be recovered. Download anything you want to keep before proceeding.
</p>

<div class="mt-5">
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete my account') }}</x-danger-button>
</div>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
        @csrf
        @method('delete')

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100">
            <svg class="h-6 w-6 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
        </div>

        <h2 class="mt-4 text-lg font-semibold text-slate-950">Delete account permanently?</h2>

        <p class="mt-2 text-sm text-slate-600">
            This cannot be undone. All files, folders, and data will be deleted forever. Enter your password to confirm.
        </p>

        <div class="mt-6">
            <x-input-label for="password" value="{{ __('Your password') }}" />
            <x-text-input
                id="password"
                name="password"
                type="password"
                class="mt-2 block w-full"
                placeholder="Enter your password"
            />
            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button>
                {{ __('Yes, delete my account') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
