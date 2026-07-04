<x-app-layout>
<div>
    {{-- Header --}}
    <div class="border-b border-gray-200 bg-white px-6 py-4">
        <h1 class="text-lg font-bold text-gray-900">Profile settings</h1>
        <p class="mt-0.5 text-sm text-gray-500">Manage your name, email, password, and account.</p>
    </div>

    <div class="mx-auto max-w-2xl space-y-6 p-6">

        {{-- Profile info --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Profile information</h2>
                <p class="mt-0.5 text-xs text-gray-500">Update your display name and email address.</p>
            </div>
            <div class="px-6 py-5">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Password --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-gray-900">Change password</h2>
                <p class="mt-0.5 text-xs text-gray-500">Use a long, random password to stay secure.</p>
            </div>
            <div class="px-6 py-5">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Danger zone --}}
        <div class="overflow-hidden rounded-xl border border-red-200 bg-red-50/30">
            <div class="border-b border-red-200/60 px-6 py-4">
                <h2 class="text-sm font-semibold text-red-800">Danger zone</h2>
                <p class="mt-0.5 text-xs text-red-600/80">Permanently delete your account and all data.</p>
            </div>
            <div class="px-6 py-5">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>
</x-app-layout>
