<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eLibrary - Your Digital Knowledge Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Glossy Gradient Background */
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef, #dee2e6);
            min-height: 100vh;
            font-family: 'Roboto', sans-serif;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 0 0 20px 20px;
        }

        /* Hero Section */
        .hero {
            padding: 60px 20px;
            background: linear-gradient(135deg, #4e73df, #224abe);
            border-radius: 20px;
            color: white;
            text-align: center;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
        }

        .hero h1 {
            font-weight: 700;
        }

        /* Carousel */
        .carousel-item img {
            height: 300px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
            max-height: 100%; /* Added for responsiveness */
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.6);
            border-radius: 12px;
            padding: 15px;
        }

        /* Buttons */
        .btn-custom {
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Footer */
        footer {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 -3px 12px rgba(0,0,0,0.1);
        }

        /* Search Bar */
        .search-form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            @php
                $logoPath = DB::table('settings')->where('key', 'site_logo')->value('value') ?? 'logos/default.png';
            @endphp
            <img src="{{ secure_asset('storage/' . $logoPath) }}?t={{ time() }}" alt="Site Logo" class="site-logo" style="width:150px; height:40px">
            <a class="navbar-brand fw-bold text-primary" href="#">📚 eLibrary</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link">Welcome, {{ Auth::user()->name }}</span>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('password.request') }}" class="nav-link">Forgot your password?</a>
                        </li>
                        @if (Auth::user()->role_id === 1 || Auth::user()->role_id === 2)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.classes.index') }}">Manage Classes</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container my-5">
        <div class="hero">
            <h1>Welcome to Upperlink eLibrary</h1>
            <p class="lead">Explore a world of knowledge with our digital library platform.</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="container my-4 text-center">
        <form action="{{ 'search' }}" method="GET" class="d-flex justify-content-center search-form">
            <input type="text" class="form-control w-50" name="query" placeholder="Search for books or resources...">
            <button type="submit" class="btn btn-custom btn-primary ms-2">Search</button>
        </form>
    </div>

    <!-- Carousel -->
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-10 offset-md-1">
                <h2 class="text-center mb-4">Why Choose Our eLibrary?</h2>
                <div id="elibraryCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#elibraryCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#elibraryCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#elibraryCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#elibraryCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        <button type="button" data-bs-target="#elibraryCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ secure_asset('img/imgc3.jpg') }}" class="d-block w-100" alt="Extensive Collection">
                            <div class="carousel-caption">
                                <h3>Extensive Collection</h3>
                                <p>Thousands of books, journals, and resources across all disciplines.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ secure_asset('img/imgc1.jpg') }}" class="d-block w-100" alt="Powerful Search">
                            <div class="carousel-caption">
                                <h3>Powerful Search</h3>
                                <p>Easily find books by title, author, or category.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ secure_asset('img/imgc2.jpg') }}" class="d-block w-100" alt="Seamless Access">
                            <div class="carousel-caption">
                                <h3>Seamless Access</h3>
                                <p>Read online or download for offline use.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="{{ secure_asset('img/imgc4.jpg') }}" class="d-block w-100" alt="Personalized Accounts">
                            <div class="carousel-caption">
                                <h3>Personalized Accounts</h3>
                                <p>Track borrowing, reservations, and reading history.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="https://source.unsplash.com/800x300/?admin,dashboard" class="d-block w-100" alt="Admin Management">
                            <div class="carousel-caption">
                                <h3>Admin Management</h3>
                                <p>Tools for admins to manage books, classes, and users.</p>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#elibraryCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#elibraryCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('resources.index') }}" class="btn btn-primary btn-custom">Browse Books</a>
                    @if (Auth::check() && (Auth::user()->role_id === 1 || Auth::user()->role_id === 2))
                        <a href="{{ route('admin.classes.index') }}" class="btn btn-secondary btn-custom">Manage Classes</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-3 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} eLibrary. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>