@extends('errors.layout')

@section('title', __('Service Unavailable'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">503</div>
                    <h1 class="error-title">Service Unavailable</h1>
                    <p class="error-description">
                        The service is temporarily unavailable due to maintenance or high traffic. 
                        We're working hard to restore normal service as soon as possible. Please try again in a few minutes.
                    </p>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="bi bi-house-fill me-2"></i>
                            Go to Homepage
                        </a>
                        <a href="javascript:setTimeout(() => window.location.reload(), 10000)" class="btn-back">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Try Again Later
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
