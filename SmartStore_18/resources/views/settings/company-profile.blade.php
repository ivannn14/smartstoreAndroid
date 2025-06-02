@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Company Profile</h5>
                        <small class="text-muted">Add/Update Company Profile</small>
                    </div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Company Profile</li>
                        </ol>
                    </nav>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Please check the form for errors
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('settings.company-profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow-none border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Company Name<span class="text-danger">*</span></label>
                                            <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" 
                                                   value="{{ old('company_name', $profile->company_name ?? '') }}" required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile<span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                        <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" 
                                                               value="{{ old('mobile', $profile->mobile ?? '') }}" required>
                                                    </div>
                                                    @error('mobile')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                                               value="{{ old('phone', $profile->phone ?? '') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                       value="{{ old('email', $profile->email ?? '') }}" required>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Website</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                <input type="url" name="website" class="form-control @error('website') is-invalid @enderror" 
                                                       value="{{ old('website', $profile->website ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card shadow-none border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Address Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Country</label>
                                            <select name="country" class="form-select @error('country') is-invalid @enderror">
                                                <option value="">Select Country</option>
                                                <option value="Philippines" {{ (old('country', $profile->country ?? '') == 'Philippines') ? 'selected' : '' }}>Philippines</option>
                                                <option value="Malaysia" {{ (old('country', $profile->country ?? '') == 'Malaysia') ? 'selected' : '' }}>Malaysia</option>
                                                <option value="Singapore" {{ (old('country', $profile->country ?? '') == 'Singapore') ? 'selected' : '' }}>Singapore</option>
                                                <option value="Indonesia" {{ (old('country', $profile->country ?? '') == 'Indonesia') ? 'selected' : '' }}>Indonesia</option>
                                                <option value="Thailand" {{ (old('country', $profile->country ?? '') == 'Thailand') ? 'selected' : '' }}>Thailand</option>
                                                <option value="Vietnam" {{ (old('country', $profile->country ?? '') == 'Vietnam') ? 'selected' : '' }}>Vietnam</option>
                                                <option value="Japan" {{ (old('country', $profile->country ?? '') == 'Japan') ? 'selected' : '' }}>Japan</option>
                                                <option value="South Korea" {{ (old('country', $profile->country ?? '') == 'South Korea') ? 'selected' : '' }}>South Korea</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">City<span class="text-danger">*</span></label>
                                                    <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                                           value="{{ old('city', $profile->city ?? '') }}" required>
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Postal Code</label>
                                                    <input type="text" name="postcode" class="form-control @error('postcode') is-invalid @enderror" 
                                                           value="{{ old('postcode', $profile->postcode ?? '') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Address<span class="text-danger">*</span></label>
                                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                                      rows="3" required>{{ old('address', $profile->address ?? '') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="card shadow-none border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Company Logo</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                                <small class="text-muted d-block mt-1">
                                                    <i class="fas fa-info-circle"></i>
                                                    Recommended: Max Width/Height: 1000px * 1000px & Size: 1024kb
                                                </small>
                                                @error('logo')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 text-end">
                                                @if(isset($profile->logo))
                                                    <img src="{{ asset('storage/' . $profile->logo) }}" 
                                                         alt="Company Logo" class="img-thumbnail" style="max-height: 100px">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-warning me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add phone number formatting
    const phoneInputs = document.querySelectorAll('input[name="mobile"], input[name="phone"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    });

    // Add postcode validation
    const postcodeInput = document.querySelector('input[name="postcode"]');
    postcodeInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
});
</script>
@endpush
@endsection