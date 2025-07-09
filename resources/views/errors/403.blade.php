@extends('errors.layout')

@section('title', __('Forbidden'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">403</div>
                    <h1 class="error-title">Access Forbidden</h1>
                    <p class="error-description">
                        You don't have permission to access this resource on this server. 
                        The page you're trying to access is restricted and requires special authorization.
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
