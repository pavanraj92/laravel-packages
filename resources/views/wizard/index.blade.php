<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Install Laravel Project</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend/theme.css') }}" />

    <script>
        base_url = "{{ url('/') }}";
    </script>

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
            /* background: linear-gradient(135deg, #f4812033 0%, #f48120cc 100%); */
            font-family: 'Arial', sans-serif;
        }

        /* Main content area should take remaining space */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Footer should stick to bottom */
        footer {
            margin-top: auto;
        }

        /* Form styles */
        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        /* Prevent body scroll lock */
        body.select2-open {
            overflow: visible !important;
        }

        .loader {
            display: inline-block;
            width: 1.2em;
            height: 1.2em;
            border: 2px solid #ccc;
            border-top: 2px solid #198754;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            margin-right: 5px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .password-toggle {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 48px;
            right: 15px;
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .industry-text-color {
            color: #f48120;
        }

        #industryDescription .alert {
            font-size: 0.95rem;
            background: #e9f5ff;
            border-color: #b6e0fe;
            color: #084298;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        #industryDescription i.fa-info-circle {
            font-size: 1.2rem;
        }

        #industryDescription .industry-text strong {
            color: #0d6efd;
        }

        #industryDescription .industry-text span {
            color: #0d6efd;
            font-weight: 500;
        }
    </style>
</head>

<body>
    @php
    use Illuminate\Support\Facades\Session;
    $industry = Session::get('industry');
    $websiteName = Session::get('db')['websiteName'] ?? '';
    $dbName = Session::get('db')['dbName'] ?? '';
    $dbUser = Session::get('db')['dbUser'] ?? '';
    $dbPassword = Session::get('db')['dbPassword'] ?? '';
    $packages = Session::get('packages', []);
    $adminEmail = Session::get('adminEmail');
    $adminPassword = Session::get('adminPassword');
    $packages = is_array($packages) ? $packages : [];
    $packages = array_map(function ($pkg) {
    return is_array($pkg) ? $pkg['name'] : $pkg;
    }, $packages);
    $packages = array_filter($packages); // Remove empty values
    $packages = array_unique($packages); // Ensure unique package names
    $packages = implode(', ', $packages);
    $industryAryList = config('constants.industryAryList');
    $strIndustrySelector = '';

    @endphp

    @include('layouts.nav-header')

    <div class="main-content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-8 offset-md-2 mb-5">
                    <div class="card" style="box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); border-radius: 10px;">
                        <div class="card-header">
                            <h3 class="text-center mt-3">Laravel Admin Panel Setup</h3>
                            <p class="text-center text-muted">Follow the steps to set up your Laravel admin panel.</p>
                        </div>
                        <div class="card-body">
                            <form id="multiStepForm" novalidate autocomplete="off">
                                <!-- Step 1: Industry Selection -->
                                <div class="form-step @if (empty($industry)) active @endif"
                                    data-step="1">
                                    <h4 class="mb-3">Step 1: Select Your Industry</h4>
                                    <div class="mb-3">
                                        <label for="industry" class="form-label">Select Your Industry<span
                                                class="text-danger">*</span> (Select an industry to see what packages will be installed.)</label>
                                        <select id="industry" name="industry" class="form-select" required>
                                            <option value="">Select an industry</option>
                                            @if (isset($industryAryList) && !empty($industryAryList))
                                            @foreach ($industryAryList as $key => $value)
                                            <option value="{{ $key }}"
                                                @if ($industry==$key) selected @endif>
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                        <div id="industryDescription" class="form-text text-muted mt-2">
                                        </div>
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" value="1" id="is_dummy_data" name="is_dummy_data">
                                            <label class="form-check-label" for="is_dummy_data">
                                                Insert Dummy Data in Database.
                                            </label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Please select an industry.
                                        </div>
                                    </div>
                                    <button type="button" class="btn-outline-success next-step">Next</button>
                                </div>
                                <!-- Step 2: Database Configuration -->
                                <div class="form-step @if (empty($dbName) && empty($websiteName) && !empty($industry)) active @endif" data-step="2">
                                    <h4 class="mb-3">Step 2: Database Configuration</h4>
                                    <div class="mb-3">
                                        <label for="websiteName" class="form-label">Website Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="websiteName" name="websiteName" placeholder="Enter your website name"
                                            value="@if (isset($websiteName) && !empty($websiteName)) {{ $websiteName }} @endif"
                                            required>
                                        <div class="invalid-feedback">
                                            Website name is required.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dbName" class="form-label">Database Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dbName" name="dbName"
                                            value="@if (isset($dbName) && !empty($dbName)) {{ $dbName }} @endif" placeholder="Enter your database name"
                                            required>
                                        <div class="invalid-feedback">
                                            Database name is required.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dbUser" class="form-label">Database Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dbUser" name="dbUser" placeholder="Enter database username (e.g., root)"
                                            value="@if (isset($dbUser) && !empty($dbUser)) {{ $dbUser }} @endif"
                                            required autocomplete="off">
                                        <div class="invalid-feedback">
                                            Username is required.
                                        </div>
                                    </div>
                                    <div class="mb-3 password-toggle">
                                        <label for="dbPassword" class="form-label">Database Password</label>
                                        <input type="password" class="form-control" id="dbPassword" name="dbPassword"
                                            value="@if (isset($dbPassword) && !empty($dbPassword)) {{ $dbPassword }} @endif" autocomplete="off" placeholder="Enter database password (leave empty if none)">
                                        <span toggle="#dbPassword" class="fa fa-fw fa-eye-slash toggle-password"></span>
                                        <div class="invalid-feedback">
                                            Password is required.
                                        </div>
                                    </div>
                                    <button type="button" class="btn-outline-secondary prev-step">Previous</button>
                                    <button type="button" class="btn-outline-success next-step">Next</button>
                                </div>

                                <!-- Step 3: Admin Credentials -->
                                <div class="form-step @if (empty($adminEmail) && !empty($packages) && !empty($dbName) && !empty($websiteName) && !empty($industry)) active @endif"
                                    data-step="3">
                                    <h4 class="mb-3">Step 3: Admin Credentials</h4>
                                    <div class="mb-3">
                                        <label for="adminEmail" class="form-label">Admin Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="adminEmail" name="adminEmail"
                                            required>
                                        <div class="invalid-feedback">
                                            Valid admin email is required.
                                        </div>
                                    </div>
                                    <div class="mb-3 password-toggle">
                                        <label for="adminPassword" class="form-label">Admin Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" class="form-control" id="adminPassword"
                                            name="adminPassword" required>
                                        <span toggle="#adminPassword" class="fa fa-fw fa-eye-slash toggle-password"></span>
                                        <div class="invalid-feedback">
                                            Admin password is required.
                                        </div>
                                    </div>
                                    <button type="button" class="btn-outline-secondary prev-step">Previous</button>
                                    <button type="submit" class="btn-outline-success">Submit</button>
                                </div>
                            </form>
                            <div class="alert alert-success mt-3" id="formSuccess" style="display:none;">
                                Your Laravel admin panel has been successfully set up!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('layouts.footer')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="{{ asset('backend/theme.js') }}"></script>

    <script>
        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif

        @if(session('info'))
        toastr.info("{{ session('info') }}");
        @endif

        @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
        @endif
    </script>

    <script>
        const industrySelect = $('#industry');
        const descriptionDiv = $('#industryDescription');


        const industryDescriptions = {
            'ecommerce': `<div class="alert alert-gradient d-flex align-items-start p-3 rounded-4 shadow-sm" role="alert" style="border-color:#b6e0fe ;background: background: #e9f5ff;">
            <i class="fa fa-shopping-cart fa-2x me-3 mt-1" aria-hidden="true"></i>
            <div class="industry-text">
                <h5 class="mb-1 fw-bold">Basic Packages</h5>
                <p class="mb-0">
                    Includes <strong>Admin Auth, User, User Roles, Settings</strong> packages installed automatically.
                </p>
                <h5 class="my-1 fw-bold">E-commerce Packages</h5>
                <p class="mb-0">
                    Includes <strong>Brand, Category, Product</strong> packages installed automatically.
                </p>
            </div>
        </div>`,
            'education': `<div class="alert alert-gradient d-flex align-items-start p-3 rounded-4 shadow-sm" role="alert" style="style="background: background: #e9f5ff; color: #fff;">
            <i class="fa fa-graduation-cap fa-2x me-3 mt-1" aria-hidden="true"></i>
            <div class="industry-text">
                <h5 class="mb-1 fw-bold">Basic Packages</h5>
                <p class="mb-0">
                    Includes <strong>Admin Auth, User, User Roles, Settings</strong> packages installed automatically.
                </p>
                <h5 class="my-1 fw-bold">Education Packages</h5>
                <p class="mb-0">
                    Includes <strong>Category, Course & Lecture</strong> packages installed automatically.
                </p>
            </div>
        </div>`
        };

        // Listen to Select2 change
        industrySelect.on('change', function() {
            const selected = $(this).val();
            if (selected && industryDescriptions[selected]) {
                descriptionDiv.html(industryDescriptions[selected]);
            } else {
                descriptionDiv.html('Select an industry to see what packages will be installed.');
            }
        });

        // Trigger change on page load to set initial description
        document.addEventListener("DOMContentLoaded", function() {
            const toggles = document.querySelectorAll(".toggle-password");
            toggles.forEach(function(toggle) {
                toggle.addEventListener("click", function() {
                    const input = document.querySelector(this.getAttribute("toggle"));
                    const type = input.getAttribute("type") === "password" ? "text" : "password";
                    input.setAttribute("type", type);

                    // Toggle icon class
                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            });
        });

        $(document).ready(function() {
            let fakeProgressIntervals = {}; // Track fake progress
            let currentProgress = {}; // Track current % per package

            function startFakeProgress(pkgValue) {
                let progress = currentProgress[pkgValue] || 0;
                const $text = $(`.package-status[data-package="${pkgValue}"] .progress-text`);

                // Stop any existing interval
                if (fakeProgressIntervals[pkgValue]) {
                    clearInterval(fakeProgressIntervals[pkgValue]);
                }

                fakeProgressIntervals[pkgValue] = setInterval(() => {
                    if (progress < 90) {
                        let increment = Math.floor(Math.random() * 3) + 1; // 1-3%
                        progress = Math.min(progress + increment, 90);
                        currentProgress[pkgValue] = progress;
                        $text.text('Processing...' + progress + '%');
                    }
                }, 500 + Math.random() * 500); // Random interval between 300–600ms
            }

            function completeFakeProgress(pkgValue) {
                if (fakeProgressIntervals[pkgValue]) {
                    clearInterval(fakeProgressIntervals[pkgValue]);
                    delete fakeProgressIntervals[pkgValue];
                }

                let progress = currentProgress[pkgValue] || 90;
                const $text = $(`.package-status[data-package="${pkgValue}"] .progress-text`);

                // Don’t restart from 0 — continue where it left off
                let interval = setInterval(() => {
                    if (progress < 100) {
                        progress++;
                        currentProgress[pkgValue] = progress;
                        $text.text('Processing...' + progress + '%');
                    } else {
                        clearInterval(interval);
                        $(`.package-status[data-package="${pkgValue}"]`)
                            .show()
                            .removeClass()
                            .addClass('ms-auto badge theme-bg-color')
                            .html('Installed');
                    }
                }, 50); // Smooth finish
            }

            let packageStatus = {}; // e.g. { 'vendor/name': 'pending'|'in-process'|'installed' }

            $('#industry').select2({
                placeholder: "Select an industry",
                allowClear: true,
                minimumResultsForSearch: Infinity,
                dropdownParent: $('#industry').parent(),
                width: '100%',
                dropdownAutoWidth: false,
                scrollAfterSelect: false,
                closeOnSelect: true,
                dropdownCssClass: 'select2-dropdown-custom'
            });

            // Prevent page scroll when Select2 opens
            $('#industry').on('select2:open', function(e) {
                // Prevent default scroll behavior
                e.preventDefault();

                // Get current scroll position
                const scrollTop = $(window).scrollTop();

                // Keep the scroll position fixed
                setTimeout(function() {
                    $(window).scrollTop(scrollTop);
                }, 1);
            });

            // Ensure proper focus management
            $('#industry').on('select2:close', function(e) {
                $(this).focus();
            });

            function showStep(step) {
                $('#packageError').hide(); // <-- Always hide at the start
                $('.form-step').removeClass('active');
                $('.form-step[data-step="' + step + '"]').addClass('active');
                var $step = $('.form-step[data-step="' + step + '"]');
                $step.find('input, select').removeClass('is-invalid');
                $step.find('.invalid-feedback').hide();
            }

            function validateStep(step) {
                let valid = true;
                let $step = $('.form-step[data-step="' + step + '"]');
                $step.find('input, select').removeClass('is-invalid');
                $step.find('.invalid-feedback').hide();

                if (step === 1) {
                    let industry = $('#industry').val();
                    if (!industry) {
                        $('#industry').addClass('is-invalid');
                        $('#industry').parent().find('.invalid-feedback').show();
                        valid = false;
                    }
                } else if (step === 2) {
                    $step.find('input[required], select[required]').each(function() {
                        if (!this.value) {
                            $(this).addClass('is-invalid');
                            $(this).siblings('.invalid-feedback').show();
                            valid = false;
                        }
                    });
                } else if (step === 3) {
                    $step.find('input[required], select[required]').each(function() {
                        if (!this.value) {
                            $(this).addClass('is-invalid');
                            $(this).siblings('.invalid-feedback').show();
                            valid = false;
                        }
                        if (this.type === "email" && this.value) {
                            if (!this.checkValidity()) {
                                $(this).addClass('is-invalid');
                                $(this).siblings('.invalid-feedback').show();
                                valid = false;
                            }
                        }
                    });
                }
                return valid;
            }

            // AJAX endpoints (change these to your actual backend endpoints)
            const endpoints = {
                industry: base_url + '/store-industry',
                database: base_url + '/create-database',
                admin: base_url + '/store-admin-credentails'
            };

            // Next Step
            $('.next-step').click(function(event) {
                event.preventDefault();
                var activeBtn = $(this);
                var $currentStep = $('.form-step.active');
                var step = parseInt($currentStep.data('step'));
                $('#packageError').hide(); // <-- Add this line
                if (!validateStep(step)) return;

                // AJAX per step
                if (step === 1) {
                    $.ajax({
                        url: endpoints.industry,
                        method: 'POST',
                        data: {
                            industry: $('#industry').val(),
                            insert_dummy_data: $('#is_dummy_data').is(':checked') ? 1 : 0,
                            _token: $('meta[name="csrf-token"]').attr('content') // If using Laravel
                        },
                        success: function(res) {
                            // window.location.reload();
                            showStep(2);
                        },
                        error: function(xhr, status, error) {
                            console.log('❌ Error:', xhr.status, xhr.responseText);
                            let response = {};
                            try {
                                if (xhr.responseText) {
                                    response = JSON.parse(xhr.responseText);
                                }
                            } catch (e) {
                                console.error('Invalid JSON response', e);
                            }
                            if (xhr.status === 400) {
                                toastr.error(response.message);
                            } else if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    toastr.error(errors[field][
                                        0
                                    ]); // Show first error per field
                                }
                            } else {
                                toastr.error(response.message || 'Something went wrong. Please try again.');
                            }
                        }
                    });
                } else if (step === 2) {
                    let $btn = $(this);
                    $btn.prop('disabled', true).text('Processing...');

                    // 1️⃣ Create Database
                    $.ajax({
                        url: endpoints.database,
                        method: 'POST',
                        data: {
                            website_name: $('#websiteName').val(),
                            db_name: $('#dbName').val(),
                            db_user: $('#dbUser').val(),
                            db_password: $('#dbPassword').val(),
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            if (res.status === 'error' || res.error) {
                                toastr.error(res.message || res.error);
                                $btn.prop('disabled', false).text('Next');
                                return;
                            }
                            toastr.success(res.message);
                            $btn.prop('disabled', true).text('Installing...');

                            // 2️⃣ Install Packages only after DB creation success
                            setTimeout(function() {
                                $.ajax({
                                    url: "{{ route('store-packages') }}",
                                    method: 'POST',
                                    data: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(res) {
                                        alert("success");
                                        toastr.success(res.message || 'Packages installed successfully!');
                                        // ✅ Show Step 3 only after packages installed
                                        showStep(3);
                                        $btn.prop('disabled', false).text('Next');
                                    },
                                    error: function(xhr) {
                                        alert("error");
                                        toastr.error(xhr.responseJSON?.message || 'Error installing packages.');
                                        $btn.prop('disabled', false).text('Next');
                                    }
                                });
                            }, 10000);

                        },
                        error: function(xhr) {
                            toastr.error(xhr.responseJSON?.message || 'Database creation failed.');
                            $btn.prop('disabled', false).text('Next');
                        }
                    });
                }
            });

            // Previous Step
            $('.prev-step').click(function() {
                var $currentStep = $('.form-step.active');
                var step = parseInt($currentStep.data('step'));
                $('#packageError').hide(); // <-- Add this line
                showStep(step - 1);
                if (step === 3) {
                    showStep(2);
                } else {
                    showStep(step - 1);
                }
            });

            // Remove validation error on change/input
            $('input, select').on('input change', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').hide();
                }
                if ($(this).attr('id') === 'industry') {
                    $(this).removeClass('is-invalid');
                    $(this).parent().find('.invalid-feedback').hide();
                }
            });

            // Final Submit (Admin Credentials)
            $('#multiStepForm').on('submit', function(e) {
                e.preventDefault();
                if (!validateStep(3)) return;

                // Get dummy data flag from session (or hidden input)
                let insertDummy = "{{ session('insert_dummy_data', 0) }}"; // 0 if not set
                $.ajax({
                    url: endpoints.admin,
                    method: 'POST',
                    data: {
                        admin_email: $('#adminEmail').val(),
                        admin_password: $('#adminPassword').val(),
                        is_dummy_data: insertDummy,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('#formSuccess').show();
                        setTimeout(function() {
                            $('#formSuccess').fadeOut();
                            $('#multiStepForm')[0].reset();
                            $('#industry').val(null).trigger('change');

                            window.location.href = res.redirect_url;
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        console.log('❌ Error:', xhr.status, xhr.responseText);
                        let response = {};
                        try {
                            if (xhr.responseText) {
                                response = JSON.parse(xhr.responseText);
                            }
                        } catch (e) {
                            console.error('Invalid JSON response', e);
                        }
                        // Remove previous errors
                        $('#adminEmail, #adminPassword').removeClass('is-invalid');
                        $('#adminEmail').siblings('.invalid-feedback').hide();
                        $('#adminPassword').siblings('.invalid-feedback').hide();
                        if (xhr.status === 400) {
                            if (response.message && response.message.toLowerCase().includes(
                                    'email')) {
                                $('#adminEmail').addClass('is-invalid');
                                $('#adminEmail').siblings('.invalid-feedback').text(response
                                    .message).show();
                            } else if (response.message && response.message.toLowerCase()
                                .includes('password')) {
                                $('#adminPassword').addClass('is-invalid');
                                $('#adminPassword').siblings('.invalid-feedback').text(response
                                    .message).show();
                            } else {
                                toastr.error(response.message);
                            }
                        } else if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                if (field === 'admin_email') {
                                    $('#adminEmail').addClass('is-invalid');
                                    $('#adminEmail').siblings('.invalid-feedback').text(errors[
                                        field][0]).show();
                                }
                                if (field === 'admin_password') {
                                    $('#adminPassword').addClass('is-invalid');
                                    $('#adminPassword').siblings('.invalid-feedback').text(
                                        errors[field][0]).show();
                                }
                            }
                        } else {
                            toastr.error(response.message || 'Something went wrong. Please try again.');
                        }
                    }
                });
            });
        });
    </script>




</body>

</html>