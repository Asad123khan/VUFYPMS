@extends('layouts.vufypms')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4">{{ __('Profile') }}</h1>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        {{ __('Profile Information') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        {{ __('Update Password') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="card mb-4 shadow-sm border-danger">
                    <div class="card-header bg-danger text-white">
                        {{ __('Delete Account') }}
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
