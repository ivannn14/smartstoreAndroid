@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="card card-body mx-3 mx-md-4 mt-4">
        <div class="row">
            <!-- Profile Header -->
            <div class="col-12">
                <div class="profile-header bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 mb-4 animate__animated animate__fadeInDown">
                    <div class="row px-4 align-items-center">
                        <div class="col-auto">
                            <div class="avatar avatar-xl position-relative">
                                <img src="{{ Auth::user()->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                                     alt="profile_image" 
                                     class="w-100 border-radius-lg shadow-sm">
                            </div>
                        </div>
                        <div class="col-auto my-auto">
                            <h5 class="mb-1 text-white">{{ Auth::user()->name }}</h5>
                            <p class="mb-0 font-weight-normal text-white opacity-8">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card profile-card animate__animated animate__fadeInLeft">
                    <div class="card-header p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle text-primary me-2" aria-hidden="true"></i>
                            <h6 class="mb-0" id="profileInfoHeader">Profile Information</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="profile-info">
                            <!-- Name Section with Edit -->
                            <div class="info-group mb-4" role="group" aria-labelledby="fullNameLabel">
                                <label id="fullNameLabel" class="text-muted small">Full Name</label>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div id="nameDisplay" class="name-display">
                                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                    </div>
                                    <button class="btn btn-link text-primary p-0" 
                                            id="editNameBtn"
                                            aria-label="Edit full name"
                                            aria-expanded="false"
                                            aria-controls="editNameForm">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                        <span class="visually-hidden">Edit name</span>
                                    </button>
                                </div>
                                
                                <!-- Edit Name Form (Initially Hidden) -->
                                <form id="editNameForm" 
                                      class="mt-2 d-none" 
                                      action="{{ route('profile.update') }}" 
                                      method="POST"
                                      aria-labelledby="editNameFormLabel">
                                    @csrf
                                    @method('PUT')
                                    <div class="edit-name-container">
                                        <div class="input-group">
                                            <input type="text" 
                                                   class="form-control form-control-sm bg-light" 
                                                   name="name" 
                                                   id="nameInput"
                                                   value="{{ Auth::user()->name }}"
                                                   aria-label="New full name"
                                                   required>
                                            <button type="submit" 
                                                    class="btn btn-sm btn-primary"
                                                    aria-label="Save name changes">
                                                <i class="fas fa-check" aria-hidden="true"></i>
                                                <span class="visually-hidden">Save</span>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-light" 
                                                    id="cancelNameEdit"
                                                    aria-label="Cancel name edit">
                                                <i class="fas fa-times" aria-hidden="true"></i>
                                                <span class="visually-hidden">Cancel</span>
                                            </button>
                                        </div>
                                        @error('name')
                                            <div class="text-danger small mt-1" role="alert">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </form>
                            </div>

                            <!-- Email Address (Read-only) -->
                            <div class="info-group mb-4" role="group" aria-labelledby="emailLabel">
                                <label id="emailLabel" class="text-muted small">Email Address</label>
                                <div class="readonly-info">
                                    <h6 class="mb-0">{{ Auth::user()->email }}</h6>
                                </div>
                            </div>

                            <!-- Member Since (Read-only) -->
                            <div class="info-group mb-4" role="group" aria-labelledby="memberSinceLabel">
                                <label id="memberSinceLabel" class="text-muted small">Member Since</label>
                                <div class="readonly-info">
                                    <h6 class="mb-0">{{ Auth::user()->created_at->format('M d, Y') }}</h6>
                                </div>
                            </div>

                            <!-- Account Status (Read-only) -->
                            <div class="info-group" role="group" aria-labelledby="statusLabel">
                                <label id="statusLabel" class="text-muted small">Account Status</label>
                                <div class="readonly-info">
                                    <span class="badge bg-success px-3 py-2" role="status">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Password Section -->
            <div class="col-12 col-lg-6 mb-4">
                <div class="card profile-card animate__animated animate__fadeInRight">
                    <div class="card-header p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lock text-warning me-2"></i>
                            <h6 class="mb-0">Security Settings</h6>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="password-section">
                            <div class="password-strength mb-4">
                                <h6 class="mb-3">Password Strength</h6>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span class="text-muted small">Last updated: {{ Auth::user()->password_updated_at ?? 'Never' }}</span>
                                    <span class="text-success small">
                                        <i class="fas fa-check-circle me-1"></i>Strong
                                    </span>
                                </div>
                            </div>

                            <div class="password-form">
                                @include('partials.update-password-form')
                            </div>

                            <div class="password-tips mt-4">
                                <h6 class="mb-3">Password Requirements:</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Minimum 8 characters
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        At least one uppercase letter
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        At least one number
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        At least one special character
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Account Section -->
            <div class="col-12">
                <div class="card profile-card animate__animated animate__fadeInUp">
                    <div class="card-header p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            <h6 class="mb-0 text-danger">Danger Zone</h6>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="delete-account-wrapper">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-2">Delete Account</h6>
                                    <p class="text-muted mb-0">
                                        Once your account is deleted, all of its resources and data will be permanently deleted.
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteAccountModal">
                                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Account Modal -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                                <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <div class="avatar avatar-xxl mb-3">
                                    <div class="warning-icon-circle">
                                        <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                    </div>
                                </div>
                                <h5 class="mb-3">Are you sure you want to delete your account?</h5>
                                <p class="text-muted">
                                    This action is irreversible. All your data will be permanently deleted.
                                </p>
                            </div>
                            
                            <form method="post" action="{{ route('profile.destroy') }}" class="p-3">
                                @csrf
                                @method('delete')

                                <div class="form-group mb-3">
                                    <label class="form-label">Please enter your password to confirm</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control border-start-0 ps-0" 
                                               name="password" 
                                               placeholder="Enter your password"
                                               required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" 
                                            class="btn btn-light" 
                                            data-bs-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                            class="btn btn-danger delete-btn">
                                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="col-12 col-xl-4">
                <div class="card profile-card animate__animated animate__fadeInUp">
                    <div class="card-header">
                        <h6 class="mb-0">Account Statistics</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-card-body">
                                        <h6 class="text-sm mb-0 text-capitalize">Orders</h6>
                                        <h5 class="font-weight-bolder mb-0">0</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card">
                                    <div class="stat-card-body">
                                        <h6 class="text-sm mb-0 text-capitalize">Reviews</h6>
                                        <h5 class="font-weight-bolder mb-0">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/profile/index.js') }}"></script>
@endpush
