<x-app-layout>
    <x-slot name="header">
        <h2 class="profile-header">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="profile-container">
        <div class="form-section">
            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
@endpush
