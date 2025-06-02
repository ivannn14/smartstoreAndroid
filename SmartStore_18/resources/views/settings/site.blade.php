@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="card card-body mx-3 mx-md-4 mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card card-plain">
                    <div class="card-header pb-0">
                        <h4 class="font-weight-bolder">Site Settings</h4>
                        <p class="mb-0 text-muted">Add/Update Site Settings</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('settings.site.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Site Name<span class="text-danger">*</span></label>
                                <input type="text" name="site_name" class="form-control @error('site_name') is-invalid @enderror" 
                                       value="{{ old('site_name', $settings->site_name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Language<span class="text-danger">*</span></label>
                                <select name="language" class="form-select @error('language') is-invalid @enderror" required>
                                    <option value="English" {{ (old('language', $settings->language ?? '') == 'English') ? 'selected' : '' }}>English</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timezone<span class="text-danger">*</span></label>
                                <select name="timezone" class="form-select @error('timezone') is-invalid @enderror" required>
                                    <option value="">Select Timezone</option>
                                    <optgroup label="Asia">
                                        <option value="Asia/Manila" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Manila') ? 'selected' : '' }}>(GMT+8:00) Manila</option>
                                        <option value="Asia/Singapore" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Singapore') ? 'selected' : '' }}>(GMT+8:00) Singapore</option>
                                        <option value="Asia/Kuala_Lumpur" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Kuala_Lumpur') ? 'selected' : '' }}>(GMT+8:00) Kuala Lumpur</option>
                                        <option value="Asia/Hong_Kong" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Hong_Kong') ? 'selected' : '' }}>(GMT+8:00) Hong Kong</option>
                                        <option value="Asia/Jakarta" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Jakarta') ? 'selected' : '' }}>(GMT+7:00) Jakarta</option>
                                        <option value="Asia/Bangkok" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Bangkok') ? 'selected' : '' }}>(GMT+7:00) Bangkok</option>
                                        <option value="Asia/Tokyo" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Tokyo') ? 'selected' : '' }}>(GMT+9:00) Tokyo</option>
                                        <option value="Asia/Seoul" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Seoul') ? 'selected' : '' }}>(GMT+9:00) Seoul</option>
                                    </optgroup>
                                    <optgroup label="Pacific">
                                        <option value="Pacific/Guam" {{ (old('timezone', $settings->timezone ?? '') == 'Pacific/Guam') ? 'selected' : '' }}>(GMT+10:00) Guam</option>
                                        <option value="Pacific/Port_Moresby" {{ (old('timezone', $settings->timezone ?? '') == 'Pacific/Port_Moresby') ? 'selected' : '' }}>(GMT+10:00) Port Moresby</option>
                                    </optgroup>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date Format<span class="text-danger">*</span></label>
                                <select name="date_format" class="form-select @error('date_format') is-invalid @enderror" required>
                                    <option value="dd-mm-yyyy" {{ (old('date_format', $settings->date_format ?? '') == 'dd-mm-yyyy') ? 'selected' : '' }}>dd-mm-yyyy</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Time Format<span class="text-danger">*</span></label>
                                <select name="time_format" class="form-select @error('time_format') is-invalid @enderror" required>
                                    <option value="12 Hours" {{ (old('time_format', $settings->time_format ?? '') == '12 Hours') ? 'selected' : '' }}>12 Hours</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Currency<span class="text-danger">*</span></label>
                                <select name="currency" class="form-select @error('currency') is-invalid @enderror" required>
                                    <option value="">Select Currency</option>
                                    <option value="PHP" {{ (old('currency', $settings->currency ?? '') == 'PHP') ? 'selected' : '' }}>Philippines - Philippine Peso (₱)</option>
                                    <option value="USD" {{ (old('currency', $settings->currency ?? '') == 'USD') ? 'selected' : '' }}>United States - US Dollar ($)</option>
                                    <option value="EUR" {{ (old('currency', $settings->currency ?? '') == 'EUR') ? 'selected' : '' }}>Europe - Euro (€)</option>
                                    <option value="JPY" {{ (old('currency', $settings->currency ?? '') == 'JPY') ? 'selected' : '' }}>Japan - Japanese Yen (¥)</option>
                                    <option value="SGD" {{ (old('currency', $settings->currency ?? '') == 'SGD') ? 'selected' : '' }}>Singapore - Singapore Dollar (S$)</option>
                                    <option value="MYR" {{ (old('currency', $settings->currency ?? '') == 'MYR') ? 'selected' : '' }}>Malaysia - Malaysian Ringgit (RM)</option>
                                </select>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Timezone<span class="text-danger">*</span></label>
                                <select name="timezone" class="form-select @error('timezone') is-invalid @enderror" required>
                                    <option value="">Select Timezone</option>
                                    <option value="Asia/Manila" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Manila') ? 'selected' : '' }}>Asia/Manila</option>
                                    <option value="Asia/Singapore" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Singapore') ? 'selected' : '' }}>Asia/Singapore</option>
                                    <option value="Asia/Tokyo" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Tokyo') ? 'selected' : '' }}>Asia/Tokyo</option>
                                    <option value="Asia/Hong_Kong" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Hong_Kong') ? 'selected' : '' }}>Asia/Hong Kong</option>
                                </select>
                                @error('timezone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Number to Words Format<span class="text-danger">*</span></label>
                                <select name="number_format" class="form-select @error('number_format') is-invalid @enderror" required>
                                    <option value="Default" {{ (old('number_format', $settings->number_format ?? '') == 'Default') ? 'selected' : '' }}>Default</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Currency Symbol Placement<span class="text-danger">*</span></label>
                                <select name="currency_position" class="form-select @error('currency_position') is-invalid @enderror" required>
                                    <option value="Before Amount" {{ (old('currency_position', $settings->currency_position ?? '') == 'Before Amount') ? 'selected' : '' }}>Before Amount</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Site Logo</label>
                                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i>
                                    Max Width/Height: 300px * 300px & Size: 300px
                                </small>
                                @if(isset($settings->logo))
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $settings->logo) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px">
                                    </div>
                                @endif
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="enable_round_off" class="form-check-input" 
                                           {{ old('enable_round_off', $settings->enable_round_off ?? '') ? 'checked' : '' }}>
                                    <label class="form-check-label">Enable Round Off</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="disable_tax" class="form-check-input" 
                                           {{ old('disable_tax', $settings->disable_tax ?? '') ? 'checked' : '' }}>
                                    <label class="form-check-label">Disable Tax</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 text-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-warning me-2">
                        <i class="fas fa-times me-1"></i> Close
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection