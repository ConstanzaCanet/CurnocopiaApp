@extends('adminlte::page')
  
@section('content')
<form method="POST" action="{{ route('user-profile-information.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card card-primary">
        <div class="card-header">  
            <h3 class="card-title">{{ __('Profile Information') }}</h3>
        </div>
        <div class="card-body">
            <!-- Profile Photo -->
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div class="form-group">
                    <label for="photo">{{ __('Profile Photo') }}</label>
                    <div x-data="{photoName: null, photoPreview: null}">
                        <!-- Profile Photo File Input -->
                        <input type="file" class="form-control-file" id="photo" name="photo"
                               x-ref="photo"
                               x-on:change="
                                   photoName = $refs.photo.files[0].name;
                                   const reader = new FileReader();
                                   reader.onload = (e) => {
                                       photoPreview = e.target.result;
                                   };
                                   reader.readAsDataURL($refs.photo.files[0]);
                               ">

                        <!-- Current Profile Photo -->
                        <div class="mt-2" x-show="!photoPreview">
                            <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="img-circle elevation-2" width="100">
                        </div>

                        <!-- New Profile Photo Preview -->
                        <div class="mt-2" x-show="photoPreview" style="display: none;">
                            <img :src="photoPreview" class="img-circle elevation-2" width="100">
                        </div>

                        <button type="button" class="btn btn-secondary mt-2" x-on:click.prevent="$refs.photo.click()">{{ __('Select A New Photo') }}</button>
                        
                        @if ($this->user->profile_photo_path)
                            <button type="button" class="btn btn-danger mt-2" wire:click="deleteProfilePhoto">{{ __('Remove Photo') }}</button>
                        @endif

                        @error('photo')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Name -->
            <div class="form-group">
                <label for="name">{{ __('Name') }}</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $this->user->name) }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="form-group">
                <label for="last_name">{{ __('Last Name') }}</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $this->user->last_name) }}" required>
                @error('last_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">{{ __('Email') }}</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $this->user->email) }}" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2 text-danger">
                        {{ __('Your email address is unverified.') }}

                        <button type="button" class="btn btn-link" wire:click.prevent="sendEmailVerification">{{ __('Click here to re-send the verification email.') }}</button>
                    </p>
                    @if ($this->verificationLinkSent)
                        <p class="text-sm text-success">{{ __('A new verification link has been sent to your email address.') }}</p>
                    @endif
                @endif
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </div>
</form>
@stop