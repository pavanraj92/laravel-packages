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
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="display-4">Empowering Businesses With<br><span style="color: #f48120;">Laravel Admin Panel Solutions</span></h1>
                    <p class="lead mb-4">Professional Laravel packages with industry-standard admin panels tailored to your business needs. Transform your development process with our comprehensive administrative solutions that deliver exceptional performance and security.</p>
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

    <!-- Features Section -->
    <section class="section-padding" id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2>Why Choose Our Laravel Admin Panel?</h2>
                    <p class="lead">Discover the powerful features that make our admin panel the perfect choice for your business</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-lightning-charge-fill" style="color: #f48120;"></i>
                        </div>
                        <h5>Exceptional Performance</h5>
                        <p>Benefit from optimized speed and seamless operations, ensuring your workflows remain efficient and responsive at all times.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock-fill" style="color: #f48120;"></i>
                        </div>
                        <h5>Enterprise-Grade Security</h5>
                        <p>Protect your data with advanced security protocols and best-in-class infrastructure, giving you peace of mind for your business.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="bi bi-people-fill" style="color: #f48120;"></i>
                        </div>
                        <h5>Intuitive User Experience</h5>
                        <p>Enjoy a thoughtfully designed interface that prioritizes usability, making complex tasks simple for users of all skill levels.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding bg-light" id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2>Built by Experts, Trusted by Businesses</h2>
                    <p class="lead mb-4">With over 23+ years of experience in software development, Dotsquares has been empowering businesses with cutting-edge solutions.</p>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>15,000+ Happy Clients</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>27,000+ Projects Completed</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>1,000+ Technical Experts</span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>95% Customer Satisfaction</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="feature-card">
                        <h4 class="mb-4">Our Technology Stack</h4>
                        <div class="row">
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-code-square" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">Laravel</small>
                            </div>
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-bootstrap" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">Bootstrap</small>
                            </div>
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-database" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">MySQL</small>
                            </div>
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-git" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">Git</small>
                            </div>
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-cloud" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">AWS</small>
                            </div>
                            <div class="col-4 mb-3 text-center">
                                <i class="bi bi-gear" style="font-size: 2rem; color: #f48120;"></i>
                                <small class="d-block mt-2">DevOps</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        
    @include('layouts.footer')

     <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap Bundle JS (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('backend/theme.js') }}"></script>

    @stack('scripts')       

</body>

</html>
