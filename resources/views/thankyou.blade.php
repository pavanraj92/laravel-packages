<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thank You</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/theme.css') }}" />

    <style>
         /* Page layout for sticky footer */
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Main content area that pushes footer down */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .admin-btn-primary {
            background-color: #f48120;
            border-color: #f48120;
            color: #fff;
        }
        .admin-btn-primary:hover {
            background-color: #fff;
            border-color: #f48120;
            color: #000;
        }
    </style>
</head>

<body class="bg-light">

    @include('layouts.nav-header')

    <div class="main-content">
        <div class="container d-flex flex-column justify-content-center align-items-center">
            <div class="card border-0 shadow-lg p-4 text-center" style="max-width: 420px; width: 100%; background: var(--bs-body-bg);">
                <div class="mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="#f48120"
                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM6.97 11.03a.75.75 0 0 0 1.07 0l3.992-3.992a.75.75 0 1 0-1.06-1.06L7.5 9.439 6.03 7.97a.75.75 0 1 0-1.06 1.06l1.999 2z" />
                    </svg>
                </div>
                <h2 class="mb-2" style="color: #f48120;">Thank You!</h2>
                <p class="mb-4 text-secondary">The Laravel package has been installed successfully.<br>You can now access the admin panel to manage packages and modules.</p>
                <a href="{{ route('admin.login') }}" class="btn admin-btn-primary w-100 py-2">Go to Admin Panel</a>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Bootstrap 5 JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
