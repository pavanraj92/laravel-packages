<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multi-Step Form Example</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        base_url = "{{ url('/') }}";
    </script>

    <style>
        .form-step { display: none; }
        .form-step.active { display: block; }
        .select2-container { width: 100% !important; }
        .select2-container {
            width: 100% !important;
        }
        .select2-selection {
            height: 38px !important; /* Match Bootstrap's default input height */
            padding: 0.375rem 0.75rem !important; /* Match Bootstrap input padding */
            border: 1px solid #ced4da !important;
            border-radius: 0.375rem !important;
            background-color: #fff !important;
            display: flex !important;
            align-items: center !important;
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
            to { transform: rotate(360deg); }
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
    $packages = array_map(function($pkg) {
        return is_array($pkg) ? $pkg['name'] : $pkg;
    }, $packages);
    $packages = array_filter($packages); // Remove empty values
    $packages = array_unique($packages); // Ensure unique package names
    $packages = implode(', ', $packages);
    $industryAryList = config('constants.industryAryList');
    $strIndustrySelector = '';
// dd(Session::all());
   
@endphp
<div class="container mt-5">
    <form id="multiStepForm" novalidate autocomplete="off">
        <!-- Step 1: Industry Selection -->
        <div class="form-step @if(empty($industry)) active @endif" data-step="1">
            <h4 class="mb-3">Step 1: Industry Selection</h4>
            <div class="mb-3">
                <label for="industry" class="form-label">Choose Industry <span class="text-danger">*</span></label>
                <select id="industry" name="industry" class="form-select" required>
                    <option value="">Select an industry</option>
                    @if(isset($industryAryList) && !empty($industryAryList))
                        @foreach($industryAryList as $key => $value)                           
                            <option value="{{ $key }}" @if($industry == $key) selected @endif>{{ $value }}</option>
                        @endforeach
                    @endif                   
                </select>
                <div class="invalid-feedback">
                    Please select an industry.
                </div>
            </div>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>
        <!-- Step 2: Database Configuration -->
        <div class="form-step @if(empty($dbName) && empty($websiteName) && !empty($industry)) active @endif" data-step="2">
            <h4 class="mb-3">Step 2: Database Configuration</h4>
            <div class="mb-3">
                <label for="websiteName" class="form-label">Website Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="websiteName" name="websiteName" value="@if (isset($websiteName) && !empty($websiteName)) {{ $websiteName }} @endif" required>
                <div class="invalid-feedback">
                    Website name is required.
                </div>
            </div>
            <div class="mb-3">
                <label for="dbName" class="form-label">Database Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dbName" name="dbName" value="@if (isset($dbName) && !empty($dbName)) {{ $dbName }} @endif" required>
                <div class="invalid-feedback">
                    Database name is required.
                </div>
            </div>
            <div class="mb-3">
                <label for="dbUser" class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="dbUser" name="dbUser" value="@if (isset($dbUser) && !empty($dbUser)) {{ $dbUser }} @endif" required>
                <div class="invalid-feedback">
                    Username is required.
                </div>
            </div>
            <div class="mb-3">
                <label for="dbPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="dbPassword" name="dbPassword" value="@if (isset($dbPassword) && !empty($dbPassword)) {{ $dbPassword }} @endif">
                <div class="invalid-feedback">
                    Password is required.
                </div>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>
        <!-- Step 3: Package Selection -->
        <div class="form-step @if(empty($packages) && !empty($dbName) && !empty($websiteName) && !empty($industry)) active @endif" data-step="3">
            <h4 class="mb-3">Step 3: Package Selection: <small class="text-danger">Select at least one package.</small></h4>
            <div class="mb-3">
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="selectAllPackages">
                <label class="form-check-label fw-bold" for="selectAllPackages">
                Select All
                </label>
            </div>
            <ul class="list-group">
                @forelse($packageList as $index => $package)
                <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div>
                        <input class="form-check-input me-2 package-checkbox" type="checkbox" name="packages[]"
                        value="{{ $package['vendor'] }}/{{ $package['name'] }}" id="package_{{ $index }}">
                        <label class="form-check-label" for="package_{{ $index }}">
                        <strong>{{ $package['display_name'] ?? $package['vendor'] . '/' . $package['name'] }}</strong>
                        @if (isset($package['info']['description']))
                            – {{ $package['info']['description'] }}
                        @endif
                        </label>
                    </div>
                    <span 
                        class="package-status" 
                        id="package_status_{{ $index }}" 
                        data-package="{{ $package['vendor'] }}/{{ $package['name'] }}" 
                        style="min-width: 100px; display: none;">
                    </span>
                </li>
                @empty
                <li class="list-group-item text-muted">No packages found.</li>
                @endforelse
            </ul> 

            <!-- Example progress bar -->           
            <div id="progress-bar-container" class="mt-4 mb-4" style="display:none;">                
                <div class="text-center mt-2">
                <div class="spinner-border text-success" role="status"></div>
                <span class="ms-2">Please wait, installation in progress...</span>
                </div>
            </div> 

            <div class="invalid-feedback" id="packageError" style="display:none;">
                Please select at least one package.
            </div> 
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="button" class="btn btn-primary next-step">Next</button>
        </div>
        <!-- Step 4: Admin Credentials -->
        <div class="form-step @if(empty($adminEmail) && !empty($packages) && !empty($dbName) && !empty($websiteName) && !empty($industry)) active @endif" data-step="4">
            <h4 class="mb-3">Step 4: Admin Credentials</h4>
            <div class="mb-3">
                <label for="adminEmail" class="form-label">Admin Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="adminEmail" name="adminEmail" required>
                <div class="invalid-feedback">
                    Valid admin email is required.
                </div>
            </div>
            <div class="mb-3">
                <label for="adminPassword" class="form-label">Admin Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
                <div class="invalid-feedback">
                    Admin password is required.
                </div>
            </div>
            <button type="button" class="btn btn-secondary prev-step">Previous</button>
            <button type="submit" class="btn btn-success">Submit</button>
        </div>
    </form>
    <div class="alert alert-success mt-3" id="formSuccess" style="display:none;">
        Your Laravel application has been successfully set up!
    </div>
</div>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


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
            .addClass('ms-auto badge bg-warning package-status')
            .text('Pending');
    });

    let packageStatus = {}; // e.g. { 'vendor/name': 'pending'|'in-process'|'installed' }

    function updatePackageStatus(pkgValue, status) {
        let $status = $(`.package-status[data-package="${pkgValue}"]`);
        if (status === 'in-process') {
            $status.html('<span class="loader"></span> In Process').show();
        } else if (status === 'installed') {
            $status.html('<span class="text-success">&#10003;</span> Installed').show();
        } else {
            $status.hide();
        }
    }




    // Select All functionality
    $('#selectAllPackages').on('change', function() {
        $('.package-checkbox').prop('checked', this.checked).trigger('change');
    });

    // If any package-checkbox is unchecked, uncheck Select All
    $(document).on('change', '.package-checkbox', function() {
        if ($('.package-checkbox:checked').length === $('.package-checkbox').length) {
        $('#selectAllPackages').prop('checked', true);
        } else {
        $('#selectAllPackages').prop('checked', false);
        }
    });

    $('#industry').select2({
        placeholder: "Select an industry",
        allowClear: true,
        minimumResultsForSearch: Infinity
    });

    function showStep(step) {
        $('.form-step').removeClass('active');
        $('.form-step[data-step="' + step + '"]').addClass('active');
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
        industry: base_url+'/store-industry',
        database: base_url+'/create-database',
        packages: base_url+'/store-packages',
        admin:    base_url+'/store-admin-credentails'
    };

    // Next Step
    $('.next-step').click(function() {
        var activeBtn = $(this);
        var $currentStep = $('.form-step.active');
        var step = parseInt($currentStep.data('step'));
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
                            toastr.error(errors[field][0]); // Show first error per field
                        }
                    } else {
                        toastr.error('Something went wrong. Please try again.');
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

                    if(res.error) {
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
                            toastr.error(errors[field][0]); // Show first error per field
                        }
                    } else {
                        toastr.error('Something went wrong. Please try again.');
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
                $('#progress-bar-container').css('display', 'block');
                activeBtn.attr('disabled', true);
                activeBtn.text('Installing Packages...');
            }    

            $('.package-checkbox').each(function (idx) {
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
                        success: function (res) {
                            if (res.status === 'installed') {
                                updatePackageStatus(pkg, 'installed');
                            }
                        },
                        error: function (xhr) {
                            console.error('Check install error for', pkg, xhr.responseText);
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
                    $('#progress-bar-container').hide();
                    let delay = 0;
                    packages.forEach((pkg, index) => {
                        setTimeout(() => {
                            updatePackageStatus(pkg, 'in-process');
                            setTimeout(() => {
                                updatePackageStatus(pkg, 'installed');
                                if (index === packages.length - 1) {
                                    toastr.success(res.message);
                                    activeBtn.prop('disabled', false).text('Next');
                                    showStep(4);
                                }
                            }, 1000);
                        }, index * 1500);
                    });
                },
                error: function(xhr, status, error) {
                    activeBtn.attr('disabled', false);
                    activeBtn.text('Next');
                    console.log('❌ Error:', xhr.status, xhr.responseText);
                    response = JSON.parse(xhr.responseText);
                    if (xhr.status === 400) {
                        toastr.error(response.message);
                    } else if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (const field in errors) {
                            toastr.error(errors[field][0]); // Show first error per field
                        }
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });            
        }
    });

    // Previous Step
    $('.prev-step').click(function() {
        var $currentStep = $('.form-step.active');
        var step = parseInt($currentStep.data('step'));
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
                    if (response.message && response.message.toLowerCase().includes('email')) {
                        $('#adminEmail').addClass('is-invalid');
                        $('#adminEmail').siblings('.invalid-feedback').text(response.message).show();
                    } else if (response.message && response.message.toLowerCase().includes('password')) {
                        $('#adminPassword').addClass('is-invalid');
                        $('#adminPassword').siblings('.invalid-feedback').text(response.message).show();
                    } else {
                        toastr.error(response.message);
                    }
                } else if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        if (field === 'admin_email') {
                            $('#adminEmail').addClass('is-invalid');
                            $('#adminEmail').siblings('.invalid-feedback').text(errors[field][0]).show();
                        }
                        if (field === 'admin_password') {
                            $('#adminPassword').addClass('is-invalid');
                            $('#adminPassword').siblings('.invalid-feedback').text(errors[field][0]).show();
                        }
                    }
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            }
        });
    });
});
</script>

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

</body>
</html>