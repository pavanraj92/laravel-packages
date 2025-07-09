@extends('errors.layout')

@section('title', __('Too Many Requests'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">429</div>
                    <h1 class="error-title">Too Many Requests</h1>
                    <p class="error-description">
                        You have made too many requests in a short period of time. Please wait a moment 
                        and try again. This helps us maintain optimal performance for all users.
                    </p>
                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn-home">
                            <i class="bi bi-house-fill me-2"></i>
                            Go to Homepage
                        </a>
                        <a href="javascript:setTimeout(() => window.location.reload(), 5000)" class="btn-back">
                            <i class="bi bi-clock me-2"></i>
                            Wait & Retry
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
