@extends('errors.layout')

@section('title', __('Unauthorized'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">401</div>
                    <h1 class="error-title">Access Denied</h1>
                    <p class="error-description">
                        You don't have permission to access this resource. Please check your credentials 
                        or contact the administrator if you believe this is an error.
                    </p>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="bi bi-house-fill me-2"></i>
                            Go to Homepage
                        </a>
                        <a href="javascript:history.back()" class="btn-back">
                            <i class="bi bi-arrow-left me-2"></i>
                            Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
