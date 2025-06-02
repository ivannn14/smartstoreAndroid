@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="card card-body mx-3 mx-md-4 mt-4">
        <!-- Settings Header -->
        <div class="row">
            <div class="col-12">
                <div class="card card-plain">
                    <div class="card-header pb-0">
                        <h4 class="font-weight-bolder">Settings</h4>
                        <p class="mb-0 text-muted">Manage your account settings and preferences</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            <div class="row mt-4">
                <!-- Account Settings -->
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Email Notifications</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="emailNotifications" name="email_notifications">
                                    <label class="form-check-label" for="emailNotifications">Receive email notifications</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Two-Factor Authentication</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="twoFactor" name="two_factor_auth">
                                    <label class="form-check-label" for="twoFactor">Enable two-factor authentication</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings -->
                <div class="col-12 col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Appearance</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Theme</label>
                                <select class="form-select" id="themeSelect" name="theme">
                                    <option value="light">Light</option>
                                    <option value="dark">Dark</option>
                                    <option value="auto">Auto (System)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-select" id="languageSelect" name="language">
                                    <option value="en">English</option>
                                    <option value="es">Spanish</option>
                                    <option value="fr">French</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('themeSelect').addEventListener('change', function() {
        document.body.classList.toggle('dark-mode', this.value === 'dark');
    });
</script>

@endsection
