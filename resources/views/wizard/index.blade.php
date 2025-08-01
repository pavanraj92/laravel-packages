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

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- theme CSS -->
    <link rel="stylesheet" href="{{ asset('backend/custom.css') }}" />
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
                                                class="text-danger">*</span></label>
                                        <select id="industry" name="industry" class="form-select" required>
                                            <option value="">Select an industry</option>
                                            @if (isset($industryAryList) && !empty($industryAryList))
                                                @foreach ($industryAryList as $key => $value)
                                                    <option value="{{ $key }}"
                                                        @if ($industry == $key) selected @endif>
                                                        {{ $value }}</option>
                                                @endforeach
                                            @endif
                                        </select>
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
                                        <input type="text" class="form-control" id="websiteName" name="websiteName"
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
                                            value="@if (isset($dbName) && !empty($dbName)) {{ $dbName }} @endif"
                                            required>
                                        <div class="invalid-feedback">
                                            Database name is required.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dbUser" class="form-label">Username <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="dbUser" name="dbUser"
                                            value="@if (isset($dbUser) && !empty($dbUser)) {{ $dbUser }} @endif"
                                            required autocomplete="off">
                                        <div class="invalid-feedback">
                                            Username is required.
                                        </div>
                                    </div>
                                    <div class="mb-3 password-toggle">
                                        <label for="dbPassword" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="dbPassword" name="dbPassword"
                                            value="@if (isset($dbPassword) && !empty($dbPassword)) {{ $dbPassword }} @endif" autocomplete="off">
                                        <span toggle="#dbPassword" class="fa fa-fw fa-eye-slash toggle-password"></span>
                                        <div class="invalid-feedback">
                                            Password is required.
                                        </div>
                                    </div>
                                    <button type="button" class="btn-outline-secondary prev-step">Previous</button>
                                    <button type="button" class="btn-outline-success next-step">Next</button>
                                </div>
                                <!-- Step 3: Package Selection -->
                                <div class="form-step @if (empty($packages) && !empty($dbName) && !empty($websiteName) && !empty($industry)) active @endif" data-step="3">
                                    <h4 class="mb-3">Step 3: Select Packages: <small class="theme-text-color">Select at least one package.</small></h4>
                                    <div class="mb-3" style="max-height: 500px; overflow-y: auto;">
                                        
                                        <!-- Common Packages Section -->
                                        <div class="mb-4">
                                            <h5 class="mb-3 text-primary">
                                                <i class="fas fa-cogs me-2"></i>Common Packages
                                            </h5>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="selectAllCommonPackages">
                                                <label class="form-check-label fw-bold" for="selectAllCommonPackages">
                                                    Select All Common Packages
                                                </label>
                                            </div>
                                            <ul class="list-group mb-3">                                            
                                                @forelse($commonPackageList as $index => $package)
                                                    @php
                                                        $packageName = $package['vendor'] . '/' . $package['name'];
                                                        $strChecked = in_array($packageName, explode(', ', $packages)) ? 'checked' : '';
                                                    @endphp
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <input class="form-check-input me-2 package-checkbox common-package-checkbox"
                                                                type="checkbox" name="packages[]"
                                                                value="{{ $packageName }}"
                                                                id="common_package_{{ $index }}" {{ $strChecked }}>
                                                            <label class="form-check-label"
                                                                for="common_package_{{ $index }}" style="display: inline;" data-toggle="tooltip" data-placement="top" title="{{ (isset($package['info']['description'])) ? $package['info']['description'] : 'No description available' }}">
                                                                {{ $package['display_name'] ?? $package['vendor'] . '/' . $package['name'] }}                                                         
                                                            </label>
                                                        </div>
                                                        <span class="package-status"
                                                            id="package_status_common_{{ $index }}"
                                                            data-package="{{ $packageName }}"
                                                            style="display: none;">
                                                            <span class="progress-text" style="font-size: 14px; font-weight: bold;">Processing...0%</span>
                                                        </span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted">No common packages found.</li>
                                                @endforelse
                                            </ul>
                                        </div>

                                        <!-- Industry-Specific Packages Section -->
                                        @if(!empty($selectedIndustry) && !empty($industryPackageList))
                                        <div class="mb-4">
                                            <h5 class="mb-3 text-success">
                                                <i class="{{ config('constants.industry_icons.' . $selectedIndustry, 'fas fa-industry') }} me-2"></i>{{ config('constants.industryAryList.' . $selectedIndustry, $selectedIndustry) }} Package
                                            </h5>
                                            <p class="text-muted mb-3">
                                                The {{ config('constants.industryAryList.' . $selectedIndustry, $selectedIndustry) }} package provides essential features for building {{ strtolower(config('constants.industryAryList.' . $selectedIndustry, $selectedIndustry)) }} applications.
                                            </p>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="selectAllIndustryPackages">
                                                <label class="form-check-label fw-bold" for="selectAllIndustryPackages">
                                                    Select All {{ config('constants.industryAryList.' . $selectedIndustry, $selectedIndustry) }} Packages
                                                </label>
                                            </div>
                                            <ul class="list-group">                                            
                                                @forelse($industryPackageList as $index => $package)
                                                    @php
                                                        $packageName = $package['vendor'] . '/' . $package['name'];
                                                        $strChecked = in_array($packageName, explode(', ', $packages)) ? 'checked' : '';
                                                    @endphp
                                                    <li class="list-group-item d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <input class="form-check-input me-2 package-checkbox industry-package-checkbox"
                                                                type="checkbox" name="packages[]"
                                                                value="{{ $packageName }}"
                                                                id="industry_package_{{ $index }}" {{ $strChecked }}>
                                                            <label class="form-check-label"
                                                                for="industry_package_{{ $index }}" style="display: inline;" data-toggle="tooltip" data-placement="top" title="{{ (isset($package['info']['description'])) ? $package['info']['description'] : 'No description available' }}">
                                                                {{ $package['display_name'] ?? $package['vendor'] . '/' . $package['name'] }}                                                         
                                                            </label>
                                                        </div>
                                                        <span class="package-status"
                                                            id="package_status_industry_{{ $index }}"
                                                            data-package="{{ $packageName }}"
                                                            style="display: none;">
                                                            <span class="progress-text" style="font-size: 14px; font-weight: bold;">Processing...0%</span>
                                                        </span>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item text-muted">No industry-specific packages found.</li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        @endif

                                        <div class="invalid-feedback" id="packageError" style="display:none;">
                                            Please select at least one package.
                                        </div>
                                    </div>
                                    <button type="button" class="btn-outline-secondary prev-step">Previous</button>
                                    <button type="button" class="btn-outline-success next-step">Next</button>
                                </div>
                                <!-- Step 4: Admin Credentials -->
                                <div class="form-step @if (empty($adminEmail) && !empty($packages) && !empty($dbName) && !empty($websiteName) && !empty($industry)) active @endif"
                                    data-step="4">
                                    <h4 class="mb-3">Step 4: Admin Credentials</h4>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const toggles = document.querySelectorAll(".toggle-password");

            toggles.forEach(function (toggle) {
                toggle.addEventListener("click", function () {
                    const input = document.querySelector(this.getAttribute("toggle"));
                    const type = input.getAttribute("type") === "password" ? "text" : "password";
                    input.setAttribute("type", type);

                    // Toggle icon class
                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let packages = [];
            $('.package-checkbox:checked').each(function() {
                const packageName = $(this).val();
                console.log('Selected:', packageName);
                packages.push(packageName);

                $(`.package-status[data-package="${packageName}"]`)
                    .show()
                    .removeClass()
                    .addClass('ms-auto badge theme-bg-color')
                    .html('Installed');
            });

            // Initialize select all checkboxes
            updateSelectAllCheckboxes();

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

            function updatePackageStatus(pkgValue, status) {
                let $status = $(`.package-status[data-package="${pkgValue}"]`);
                let $text = $status.find('.progress-text');

                if (status === 'in-process') {
                    $status.show();

                    // Only initialize to 0 if not already started
                    if (typeof currentProgress[pkgValue] === 'undefined') {
                        currentProgress[pkgValue] = 0;
                        $text.text('0%');
                        startFakeProgress(pkgValue);
                    }

                } else if (status === 'installed') {
                    completeFakeProgress(pkgValue);
                } else {
                    $status.hide();
                    if (fakeProgressIntervals[pkgValue]) {
                        clearInterval(fakeProgressIntervals[pkgValue]);
                        delete fakeProgressIntervals[pkgValue];
                    }
                    delete currentProgress[pkgValue];
                }
            }



            function updateSelectAllCheckboxes() {
                // Update common packages select all
                if ($('.common-package-checkbox:checked').length === $('.common-package-checkbox').length && $('.common-package-checkbox').length > 0) {
                    $('#selectAllCommonPackages').prop('checked', true);
                } else {
                    $('#selectAllCommonPackages').prop('checked', false);
                }

                // Update industry packages select all
                if ($('.industry-package-checkbox:checked').length === $('.industry-package-checkbox').length && $('.industry-package-checkbox').length > 0) {
                    $('#selectAllIndustryPackages').prop('checked', true);
                } else {
                    $('#selectAllIndustryPackages').prop('checked', false);
                }
            }

            // Select All Common Packages functionality
            $('#selectAllCommonPackages').on('change', function() {
                $('.common-package-checkbox').prop('checked', this.checked).trigger('change');
            });

            // Select All Industry Packages functionality
            $('#selectAllIndustryPackages').on('change', function() {
                $('.industry-package-checkbox').prop('checked', this.checked).trigger('change');
            });

            // If any package-checkbox is unchecked, uncheck respective Select All
            $(document).on('change', '.package-checkbox', function() {
                updateSelectAllCheckboxes();
            });

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
            $('#industry').on('select2:open', function (e) {
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
            $('#industry').on('select2:close', function (e) {
                $(this).focus();
            });

            function showStep(step) {
                $('#packageError').hide(); // <-- Always hide at the start
                $('.form-step').removeClass('active');
                $('.form-step[data-step="' + step + '"]').addClass('active');
                if (step === 3) {
                    updateSelectAllCheckboxes();
                }
                var $step = $('.form-step[data-step="' + step + '"]');
                $step.find('input, select').removeClass('is-invalid');
                $step.find('.invalid-feedback').hide();
                // Also hide package error if on step 3
                if (step === 3) {
                    $('#packageError').hide();
                }
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
                    if ($('.package-checkbox:checked').length === 0) {                        
                        toastr.error('Please select at least one package.');
                        $('#packageError').show();
                        valid = false;
                    } else {
                        $('#packageError').hide();
                    }
                } else if (step === 4) {
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
                packages: base_url + '/store-packages',
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
                            _token: $('meta[name="csrf-token"]').attr('content') // If using Laravel
                        },
                        success: function(res) {
                            window.location.reload();
                            showStep(2);
                        },
                        error: function(xhr, status, error) {
                            console.log('❌ Error:', xhr.status, xhr.responseText);
                            response = JSON.parse(xhr.responseText);
                            if (xhr.status === 400) {
                                toastr.error(response.message);
                            } else if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    toastr.error(errors[field][
                                    0]); // Show first error per field
                                }
                            } else {
                                toastr.error(response.message || 'Something went wrong. Please try again.');
                            }
                        }
                    });
                } else if (step === 2) {
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
                            if (res.status === 'error') {
                                toastr.error(res.message);
                                return;
                            }

                            if (res.error) {
                                toastr.error(res.error);
                                return;
                            }
                            toastr.success(res.message);
                            showStep(3);
                        },
                        error: function(xhr, status, error) {
                            console.log('❌ Error:', xhr.status, xhr.responseText);
                            response = JSON.parse(xhr.responseText);
                            if (xhr.status === 400) {
                                toastr.error(response.message);
                            } else if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    toastr.error(errors[field][
                                    0]); // Show first error per field
                                }
                            } else {
                                toastr.error(response.message || 'Something went wrong. Please try again.');
                            }
                        }
                    });
                } else if (step === 3) {
                    endpoints.checkPackage = "{{ route('check.package') }}";
                    let packages = [];
                    $('.package-checkbox:checked').each(function() {
                        packages.push($(this).val());
                    });
                    if (packages.length === 0) {
                        $('#packageError').show();
                        return;
                    } else {
                        $('#packageError').hide();                        
                        activeBtn.attr('disabled', true);
                        activeBtn.text('Installing Packages...');
                        $('.prev-step').prop('disabled', true); // Disable Previous
                    }

                    $('.package-checkbox').each(function(idx) {
                        let pkg = $(this).val();
                        if ($(this).is(':checked')) {
                            packageStatus[pkg] = 'in-process';
                            updatePackageStatus(pkg, 'in-process'); // show spinner

                            // Immediately check via AJAX if already installed
                            $.ajax({
                                url: endpoints.checkPackage, // define this endpoint
                                method: 'POST',
                                data: {
                                    package: pkg,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    if (res.status === 'installed') {
                                        updatePackageStatus(pkg, 'installed');
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Check install error for', pkg, xhr
                                        .responseText);
                                }
                            });

                        } else {
                            updatePackageStatus(pkg, ''); // Hide for unselected
                        }
                    });

                    $.ajax({
                        url: endpoints.packages,
                        method: 'POST',
                        data: {
                            packages: packages,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {  
                              if (res.message === 'Packages already installed.' || res.skip_install) {
                                toastr.success(res.message);
                                setTimeout(() => {
                                    window.location.reload(); // 🔄 Refresh the page
                                }, 100);
                                // activeBtn.prop('disabled', false).text('Next');
                                // $('.prev-step').prop('disabled', false); // Re-enable Previous
                                // showStep(4);
                                return;
                            }else{                          
                                let delay = 0;
                                packages.forEach((pkg, index) => {
                                    setTimeout(() => {
                                        updatePackageStatus(pkg, 'in-process');
                                        setTimeout(() => {
                                            updatePackageStatus(pkg,
                                                'installed');
                                            if (index === packages.length -
                                                1) {
                                                toastr.success(res.message);
                                                activeBtn.prop('disabled',
                                                    false).text('Next');
                                                $('.prev-step').prop('disabled', false); // Re-enable Previous
                                                showStep(4);
                                            }
                                            window.location.reload();
                                        }, 1000);
                                    }, index * 1500);
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            activeBtn.attr('disabled', false);
                            activeBtn.text('Next');
                            $('.prev-step').prop('disabled', false); // Re-enable Previous
                            console.log('❌ Error:', xhr.status, xhr.responseText);
                            response = JSON.parse(xhr.responseText);
                            if (xhr.status === 400) {
                                toastr.error(response.message);
                            } else if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                for (const field in errors) {
                                    toastr.error(errors[field][
                                    0]); // Show first error per field
                                }
                            } else {
                                toastr.error(response.message || 'Something went wrong. Please try again.');
                            }
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

            $('.package-checkbox').on('change', function() {
                console.log('Package checkbox changed:', $('.package-checkbox:checked').length);

                if ($('.package-checkbox:checked').length > 0) {
                    $('#packageError').css('display', 'none');
                } else {
                    $('#packageError').css('display', 'block');
                }
            });

            // Final Submit (Admin Credentials)
            $('#multiStepForm').on('submit', function(e) {
                e.preventDefault();
                if (!validateStep(4)) return;
                $.ajax({
                    url: endpoints.admin,
                    method: 'POST',
                    data: {
                        admin_email: $('#adminEmail').val(),
                        admin_password: $('#adminPassword').val(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        $('#formSuccess').show();
                        setTimeout(function() {
                            $('#formSuccess').fadeOut();
                            $('#multiStepForm')[0].reset();
                            // showStep(1);
                            $('#industry').val(null).trigger('change');

                            window.location.href = res.redirect_url;
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        let response = {};
                        try {
                            response = JSON.parse(xhr.responseText);
                        } catch (e) {}

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

    <script src="{{ asset('backend/theme.js') }}"></script>

    <script>
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>


</body>

</html>
