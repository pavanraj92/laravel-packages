@extends('errors.layout')

@section('title', __('Server Error'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">500</div>
                    <h1 class="error-title">Internal Server Error</h1>
                    <p class="error-description">
                        Something went wrong on our end. We're experiencing a temporary problem with our server. 
                        Our team has been notified and is working to fix this issue as quickly as possible.
                    </p>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="bi bi-house-fill me-2"></i>
                            Go to Homepage
                        </a>
                        <a href="javascript:window.location.reload()" class="btn-back">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Try Again
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
