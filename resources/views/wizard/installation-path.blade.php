<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel Installation Path</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Main content area should take remaining space */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Footer should stick to bottom */
        footer {
            margin-top: auto;
        }

        .installation-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            background: #5a6268;
        }

        .path-preview {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #495057;
            margin-top: 10px;
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-container i {
            font-size: 2rem;
            color: white;
        }

        .step-indicator {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .validation-icons {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }

        .input-group {
            position: relative;
        }      

        .quick-path {
            min-height: 70px;
            text-align: left;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .quick-path:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .quick-path.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .quick-path.btn-primary small.text-muted {          
            color: white !important;
        }

        .system-badge {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .path-indicator {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @include('layouts.nav-header')

    <div class="main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <!-- Step Indicator -->
                    <div class="text-center mb-4">
                        <span class="step-indicator">
                            <i class="fas fa-folder-open me-2"></i>
                            Step 1: Choose Installation Location
                        </span>
                    </div>

                    <div class="installation-card p-5 mb-5">
                        @if (isset($installation_error) &&  !empty($installation_error))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ $installation_error }}
                                <ul>
                                    @foreach ($missing_requirements as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @else 
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <div class="icon-container">
                                    <i class="fas fa-download"></i>
                                </div>
                                <h2 class="h3 mb-3">Laravel Installation Setup</h2>
                                <p class="text-muted">
                                    Choose where you want to install your new Laravel project. 
                                    We'll create a new folder with your project name in the specified location.
                                </p>
                            </div>

                            <!-- Form -->
                            <form id="installationPathForm" novalidate>
                                @csrf
                                
                                <!-- Project Name -->
                                <div class="mb-4">
                                    <label for="projectName" class="form-label fw-bold">
                                        <i class="fas fa-project-diagram me-2 text-primary"></i>
                                        Project Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                            class="form-control" 
                                            id="projectName" 
                                            name="project_name"
                                            placeholder="e.g., my-laravel-app"
                                            value="{{ Session::get('project_name', '') }}"
                                            required>
                                        <div class="validation-icons"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        Only letters, numbers, hyphens and underscores allowed
                                    </small>
                                </div>

                                <!-- Installation Path -->
                                <div class="mb-4">
                                    <label for="installationPath" class="form-label fw-bold">
                                        <i class="fas fa-folder me-2 text-primary"></i>
                                        Installation Path <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                            class="form-control" 
                                            id="installationPath" 
                                            name="installation_path"
                                            placeholder="e.g., C:\laragon\www"
                                            value="{{ $currentPath ?: $defaultPath }}"
                                            required>                                    
                                        <div class="validation-icons"></div>
                                    </div>
                                    <div class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        Choose an existing directory where you have write permissions
                                    </small>
                                </div>

                                <!-- Path Preview -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-eye me-2 text-success"></i>
                                        Preview: Project will be created at
                                    </label>
                                    <div class="path-preview" id="pathPreview">
                                        <i class="fas fa-folder me-2"></i>
                                        <span id="previewPath">Please enter project name and installation path</span>
                                    </div>
                                </div>

                                <!-- Common Paths -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-star me-2 text-warning"></i>
                                        Available Installation Paths
                                        <small class="text-muted">({{ $systemInfo['name'] }} System)</small>
                                    </label>
                                    <div class="row g-2" id="commonPathsContainer">
                                        @if(!empty($commonPaths))
                                            @foreach($commonPaths as $pathInfo)
                                                <div class="col-md-6">
                                                    <button type="button" 
                                                            class="btn btn-outline-secondary w-100 quick-path" 
                                                            data-path="{{ $pathInfo['path'] }}"
                                                            title="{{ $pathInfo['type'] }}">
                                                        <i class="fas {{ $pathInfo['icon'] }} me-2"></i>
                                                        {{ $pathInfo['name'] }}
                                                        <br><small class="text-muted">{{ $pathInfo['path'] }}</small>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    No common development paths detected. You can manually enter your preferred path above.
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Refresh button -->
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-sm btn-outline-primary theme-btn" id="refreshPathsBtn">
                                            <i class="fas fa-refresh me-2"></i>
                                            Refresh Available Paths
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Back to Home
                                    </a>
                                    <button type="submit" class="btn btn-outline-success" id="continueBtn">
                                        Continue Setup
                                        <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        @endif
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
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            let systemInfo = @json($systemInfo);
            
            // Update path preview
            function updatePathPreview() {
                const projectName = $('#projectName').val().trim();
                const installationPath = $('#installationPath').val().trim();
                
                if (projectName && installationPath) {
                    const separator = systemInfo.separator;
                    const fullPath = installationPath.replace(/[\\\/]+$/, '') + separator + projectName;
                    $('#previewPath').html(`<strong>${fullPath}</strong>`);
                    $('#pathPreview').removeClass('text-muted').addClass('text-success');
                } else {
                    $('#previewPath').text('Please enter project name and installation path');
                    $('#pathPreview').removeClass('text-success').addClass('text-muted');
                }
            }

            // Update preview on input
            $('#projectName, #installationPath').on('input', updatePathPreview);

            // Quick path selection
            $(document).on('click', '.quick-path', function() {
                const path = $(this).data('path');
                $('#installationPath').val(path);
                updatePathPreview();
                
                // Add visual feedback
                $('.quick-path').removeClass('btn-primary').addClass('btn-outline-secondary');
                $(this).removeClass('btn-outline-secondary').addClass('btn-primary');
            });

            // Refresh available paths
            $('#refreshPathsBtn').on('click', function() {
                const $btn = $(this);
                const originalHtml = $btn.html();
                
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Detecting...');
                
                $.ajax({
                    url: "{{ route('get-common-paths') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            systemInfo = response.system_info;
                            updateCommonPaths(response.common_paths);
                            toastr.success('Paths refreshed successfully!');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to refresh paths.');
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html(originalHtml);
                    }
                });
            });

            // Update common paths dynamically
            function updateCommonPaths(commonPaths) {
                const container = $('#commonPathsContainer');
                
                if (commonPaths.length === 0) {
                    container.html(`
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No common development paths detected. You can manually enter your preferred path above.
                            </div>
                        </div>
                    `);
                    return;
                }
                
                let html = '';
                commonPaths.forEach(function(pathInfo) {
                    html += `
                        <div class="col-md-6">
                            <button type="button" 
                                    class="btn btn-outline-secondary w-100 quick-path" 
                                    data-path="${pathInfo.path}"
                                    title="${pathInfo.type}">
                                <i class="fas ${pathInfo.icon} me-2"></i>
                                ${pathInfo.name}
                                <br><small class="text-muted">${pathInfo.path}</small>
                            </button>
                        </div>
                    `;
                });
                container.html(html);
            }

            // Form validation
            function validateField(field) {
                const $field = $(field);
                const value = $field.val().trim();
                const $feedback = $field.closest('.input-group').siblings('.invalid-feedback');
                const $icons = $field.closest('.input-group').find('.validation-icons');
                
                $field.removeClass('is-invalid is-valid');
                $icons.empty();
                
                if (field.id === 'projectName') {
                    if (!value) {
                        $field.addClass('is-invalid');
                        $feedback.text('Project name is required.');
                        $icons.html('<i class="fas fa-times text-danger"></i>');
                        return false;
                    }
                    if (!/^[a-zA-Z0-9_-]+$/.test(value)) {
                        $field.addClass('is-invalid');
                        $feedback.text('Project name can only contain letters, numbers, hyphens and underscores.');
                        $icons.html('<i class="fas fa-times text-danger"></i>');
                        return false;
                    }
                    $field.addClass('is-valid');
                    $icons.html('<i class="fas fa-check text-success"></i>');
                    return true;
                }
                
                if (field.id === 'installationPath') {
                    if (!value) {
                        $field.addClass('is-invalid');
                        $feedback.text('Installation path is required.');
                        $icons.html('<i class="fas fa-times text-danger"></i>');
                        return false;
                    }
                    $field.addClass('is-valid');
                    $icons.html('<i class="fas fa-check text-success"></i>');
                    return true;
                }
                
                return true;
            }

            // Real-time validation
            $('#projectName, #installationPath').on('blur input', function() {
                validateField(this);
                updatePathPreview();
            });

            // Form submission
            $('#installationPathForm').on('submit', function(e) {
                e.preventDefault();
                
                // Validate all fields
                let isValid = true;
                $('#projectName, #installationPath').each(function() {
                    if (!validateField(this)) {
                        isValid = false;
                    }
                });
                
                if (!isValid) {
                    toastr.error('Please fix the validation errors before continuing.');
                    return;
                }
                
                const $btn = $('#continueBtn');
                const originalText = $btn.html();
                
                // Show loading state
                let counter = 1;
                const firstInterval = setInterval(() => {
                    if (counter < 90) {
                        $btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin me-2"></i>Installing...${counter++}`);                        
                    } else {
                        clearInterval(firstInterval); // Stop the interval on success
                    }
                }, 1000);

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Validating...');  
                
                // Submit form
                $.ajax({
                    url: "{{ route('store-installation-path') }}",
                    method: 'POST',
                    data: {
                        project_name: $('#projectName').val().trim(),
                        installation_path: $('#installationPath').val().trim(),
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {                            
                            clearInterval(firstInterval); // Stop the interval on success
                            const secondInterval = setInterval(() => {
                                if (counter <= 100) {
                                    $btn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin me-2"></i>Installing...${counter++}`);                                    
                                } else {
                                    clearInterval(secondInterval); // Stop the interval on success
                                    toastr.success(response.message);
                                    //window.location.href = "{{ route('wizard-install') }}";
                                    setTimeout(function() {
                                        window.location.href = "{{ route('wizard-install') }}";
                                    }, 2000);
                                }
                            }, 100);
                           
                        } else {
                            clearInterval(firstInterval); // Stop the interval on success
                            toastr.error(response.message || 'Something went wrong.');
                            $btn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        clearInterval(firstInterval); // Stop the interval on success
                        $btn.prop('disabled', false).html(originalText);
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const $field = $(`[name="${field}"]`);
                                const $feedback = $field.closest('.input-group').siblings('.invalid-feedback');
                                $field.addClass('is-invalid');
                                $feedback.text(errors[field][0]);
                            }
                        } else {
                            const response = xhr.responseJSON || {};
                            toastr.error(response.message || 'Something went wrong. Please try again.');
                        }
                    }
                });
            });

            // Initialize
            updatePathPreview();
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
