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

    <style>
        /* Page layout for sticky footer */
        html, body {
            height: 100%;
        }
        
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        /* Main content area should take remaining space */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        /* Footer should stick to bottom */
        footer {
            margin-top: auto;
        }

        /* 404 Page Specific Styles */
        .error-page {
            text-align: center;
            padding: 60px 20px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: #f48120;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            line-height: 1;
        }

        .error-title {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .error-description {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-home {
            background: linear-gradient(135deg, #f48120 0%, #ff6b35 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(244, 129, 32, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 129, 32, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-back {
            background: transparent;
            border: 2px solid #6c757d;
            color: #6c757d;
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #6c757d;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .error-code {
                font-size: 5rem;
            }
            
            .error-title {
                font-size: 1.5rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-home, .btn-back {
                width: 200px;
            }
        }
    </style>

</head>

<body>

    @include('layouts.nav-header')

    <div class="main-content">
        @yield('content')
    </div>

    @include('layouts.footer')

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('backend/theme.js') }}"></script>

    @stack('scripts')

</body>

</html>
