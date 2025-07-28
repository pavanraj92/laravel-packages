<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laravel Admin Panel Installation - Professional Package Setup</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet" />
   
    <!-- theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend/theme.css') }}" />

    @stack('theme-css')
      
</head>

<body>    

    @include('layouts.nav-header')

    <!-- Hero Section -->
    <main class="hero">
        <section class="hero">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12 text-center">
                        <h1 class="display-4"><span style="color: #f48120;">Laravel Admin Panel Solutions</span></h1>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="{{ route('wizard-install') }}" class="install-wizard-btn">
                                <i class="bi bi-rocket-takeoff"></i>
                                <span>Start Installation</span>
                            </a>                      
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
        
    @include('layouts.footer')

     <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('backend/theme.js') }}"></script>

    @stack('scripts')       

</body>

</html>
