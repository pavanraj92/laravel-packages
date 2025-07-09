@extends('errors.layout')

@section('title', __('Page Expired'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">419</div>
                    <h1 class="error-title">Page Expired</h1>
                    <p class="error-description">
                        Your session has expired due to inactivity. This usually happens for security reasons 
                        when you've been idle for too long. Please refresh the page to continue.
                    </p>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="bi bi-house-fill me-2"></i>
                            Go to Homepage
                        </a>
                        <a href="javascript:window.location.reload()" class="btn-back">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Refresh Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
