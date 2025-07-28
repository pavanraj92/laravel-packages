<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/dots-logo.svg') }}" alt="Logo" style="width: 100%"
                class="d-inline-block align-text-top mt-2" title="Dotsquares">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- <div class="collapse navbar-collapse" id="navbarNav">
            @if (Route::currentRouteName() === 'home')
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>                    
                </ul>
            @endif
        </div> --}}
    </div>
</nav>
