@extends('errors.layout')

@section('title', __('Payment Required'))

@section('content')
    <div class="error-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-code">402</div>
                    <h1 class="error-title">Payment Required</h1>
                    <p class="error-description">
                        This resource requires payment to access. Please ensure your subscription is active 
                        or complete the payment process to continue.
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
