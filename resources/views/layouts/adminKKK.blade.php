<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Admin Styles -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white p-3">
            <h3 class="text-white mb-4">📚 My Admin</h3>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">🏠 Dashboard</a></li>
                <li class="nav-item"><a href="{{ route('admin.resource-access.index') }}" class="nav-link text-white">📂 Resources</a></li>
                <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link text-white">👤 Users</a></li>
                <li class="nav-item"><a href="{{ route('roles.index') }}" class="nav-link text-white">🔑 Roles</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Topbar -->
            <nav class="navbar navbar-light bg-white shadow-sm px-4">
                <span class="navbar-brand mb-0 h4">Admin Panel</span>
                <div class="d-flex align-items-center">
                    <span class="me-3">Hello, {{ Auth::user()->name }}</span>
                    <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger">Logout</a>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
