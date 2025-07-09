@extends('errors.layout')

@section('title', __('Page Not Found'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">404</div>
                    <h1 class="error-title">Oops! Page Not Found</h1>
                    <p class="error-description">
                        The page you are looking for might have been removed, had its name changed, 
                        or is temporarily unavailable. Don't worry, it happens to the best of us!
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
