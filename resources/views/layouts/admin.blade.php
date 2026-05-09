
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Institution Connect') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap-select CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">

    <style>
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .site-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 4px;
        }
        .site-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #fff;
        }
        .navbar {
            background: linear-gradient(135deg, #9ab3dcff, #07172aff);
        }
        .nav-link, .dropdown-item {
            color: #fff !important;
        }
        .nav-link:hover, .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            background: linear-gradient(135deg, #031229ff, #041324ff);
            color: #fff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .modal-body {
            background: #f9fafb;
        }
        .logo-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            border-radius: 4px;
        }
    #togglePanel {
    background: linear-gradient(90deg, #4e73df, #224abe);
    border: none;
    color: #fff;
    font-weight: 500;
    padding: 0.5rem 1.2rem;
    border-radius: 50px;
    transition: all 0.3s ease-in-out;
}
#togglePanel:hover {
    background: linear-gradient(90deg, #224abe, #1a3d91);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}
        /* Background and text fixes */
.bootstrap-select .dropdown-menu {
    background-color: #fff !important;
}

.bootstrap-select .dropdown-menu li a {
    color: #212529 !important;
}

.bootstrap-select .dropdown-menu li a:hover {
    background-color: #0d6efd !important; /* Bootstrap primary */
    color: #fff !important;
}

    </style>
     <style>
        /* Full-page overlay */
        #loaderOverlay{
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,0.85);
            display: none;               /* hidden by default */
            align-items: center;
            justify-content: center;
            z-index: 20000;              /* above nav/offcanvas/modals */
            backdrop-filter: blur(1px);
        }
        /* Nice loader card */
        .loader-card{
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            min-width: 220px;
            text-align: center;
        }
        .loader-text{
            margin-top: .75rem;
            font-size: .95rem;
            color: #555;
        }
    </style>
    @yield('styles')
</head>
<body>
  
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                @php
                    $logoPath = DB::table('settings')->where('key', 'site_logo')->value('value') ?? 'logos/default.png';
                @endphp
                <img src="{{ asset('storage/' . $logoPath) }}?t={{ time() }}" alt="Site Logo" class="site-logo" style="width:150px; height:40px">
            
            @auth
    @if(Auth::user()->role_id === 1)
        <!-- <span class="site-name">{ { config('app.name', 'Institution Connect') } }</span> -->
            </a>  <a class="navbar-brand" href="{{ route('admin.dashboard') }}">eLibrary Super Admin</a> 
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
         <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">{{Auth::user()->name}}  Dashboard</a>
                    </li>
                   

                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.classes.index') }}">Classes</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('arms.index') }}">Arms</a></li>
                             <li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}">Departments</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.roles.index') }}">Roles</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('school_admin_mappers.index') }}">Map Admin to School</a></li>
                        
        
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown" onmouseover="color: blue">
                           <li class="nav-item"><a class="nav-link" href="{{ route('admin.privileges.index') }}" style="color: black !important;">Privileges</a></li>
                              <li class="nav-item"><a class="nav-link" href="{{ route('item_categories.index') }}" style="color: black !important;">Item Category</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.permissions.index') }}" style="color: black !important;">Permission</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('data_mapper.index') }}" style="color: black !important;">Data Fetcher</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.resource_types.index') }}" style="color: black !important;">Resource Types</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.resources.index') }}" style="color: black !important;">Resources</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.resource-access.index') }}" style="color: black !important;">Allocate Resources</a></li>
                    
                        <li>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoUploadModal" style="color: black !important;">
                            Change Logo
                        </a>
                        </li>     <li class="nav-item" >
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-black"  style="color: black !important;">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                    </li>                  
                 </ul>
                    </li>
                </ul>
            </div>
    @elseif(Auth::user()->role_id === 2)
        <!-- <span class="site-name">{ { config('app.name', 'Institution Connect') } }</span> -->
      <a class="navbar-brand" href="{{ route('admin.dashboard') }}">eLibrary Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
         <div class="collapse navbar-collapse" id="navbarNav">
   <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">{{Auth::user()->name}} - Dashboard</a>
                    </li>
                   

                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.classes.index') }}">Classes</a></li>
                           <li class="nav-item"><a class="nav-link" href="{{ route('arms.index') }}">Arms</a></li>
                             <li class="nav-item"><a class="nav-link" href="{{ route('departments.index') }}">Departments</a></li>
                        
        
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown" onmouseover="color: blue">
                           
                        <li class="nav-item"><a class="nav-link" href="{{ route('data_mapper.index') }}" style="color: black !important;">Data Fetcher</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.resource_types.index') }}" style="color: black !important;">Resource Types</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.resources.index') }}" style="color: black !important;">Resources</a></li>
       <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}" style="color: black !important;">Data Profile</a></li> 
                          <li class="nav-item" >
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-black"  style="color: black !important;">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                    </li>                  
                 </ul>
                    </li>
                </ul>
         </div>
         @elseif(Auth::user()->role_id === 3)
      
     <a class="navbar-brand" href="{{ route('admin.dashboard') }}">eLibrary Student</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
              <div class="collapse navbar-collapse" id="navbarNav">
   <ul class="navbar-nav ms-auto">
       <li class="nav-item">
                       <a class="nav-link" href="{{ route('student.dashboard') }}">{{Auth::user()->name}} - Dashboard</a>
                    </li>  
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Settings
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown" onmouseover="color: blue">
                            <li class="nav-item" >
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-black"  style="color: black !important;">
                                <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                    </li>     
                        <!--btn-primary <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}" style="color: black !important;">Data Profile</a></li> -->
    </ul></li> <li class="nav-item"> ------------------ <div class="toggle-button-container">
            <button class="btn"  type="button" id="togglePanel">
                Show Panel
            </button>
        </div></li> 
    </ul>
    </div>         

    @endif
@endauth
           
        </div>
    </nav>

    <!-- Logo Upload Modal -->
    <div class="modal fade" id="logoUploadModal" tabindex="-1" aria-labelledby="logoUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoUploadModalLabel">Update Site Logo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="logoUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="logo" class="form-label">Choose Logo (PNG/JPG, max 2MB)</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/png,image/jpeg" required>
                        </div>
                        <img src="{{ asset('storage/' . $logoPath) }}?t={{ time() }}" alt="Current Logo" class="logo-preview mb-3">
                        <button type="submit" class="btn btn-primary">Upload Logo</button>
                    </form>
                    <div id="logoUploadResult" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

   <!-- Toast Container -->
    <div class="toast-container">
        @if(session('error'))
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
         <!-- Toast Container -->
    <div class="toast-container">
        @if(session('error'))
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
    </div>
      <!-- Loader -->
    <div id="loaderOverlay">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <main class="py-4">
        @yield('content')
    </main>
    @if(session('success'))
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                {{ session('success') }} <br>
                <small>Table created: {{ session('api_table') }}</small>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endif

@yield('script');
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap-select JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
           $(function () {
        $('.selectpicker').selectpicker();
    });
        $(document).ready(function() {
            $('#logoUploadForm').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                $.ajax({
                    url: '{{ route("settings.update-logo") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Upload response:', response);
                        $('#logoUploadResult').html(`<div class="alert alert-success">${response.message}</div>`);
                        const newLogoUrl = response.logo_url + '?t=' + new Date().getTime();
                        $('.site-logo').attr('src', newLogoUrl).on('error', function() {
                            console.error('Failed to load logo:', newLogoUrl);
                            $(this).attr('src', '{{ asset('storage/logos/default.png') }}');
                        });
                        $('.logo-preview').attr('src', newLogoUrl).on('error', function() {
                            console.error('Failed to load preview:', newLogoUrl);
                            $(this).attr('src', '{{ asset('storage/logos/default.png') }}');
                        });
                        setTimeout(() => $('#logoUploadModal').modal('hide'), 1500);
                    },
                    error: function(err) {
                        console.error('Upload error:', err);
                        $('#logoUploadResult').html(`<div class="alert alert-danger">Error: ${err.responseJSON?.error || 'Failed to upload logo'}</div>`);
                    }
                });
            });
        });
    </script>
    @yield('scripts')
    @if(session('error'))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        alert("{{ session('error') }}");
    });
</script>
@endif

    @if(session('error'))
<!-- Toast container -->

<!-- Bootstrap JS (must be included once in layout) 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const errorToast = document.getElementById('errorToast');
            if (errorToast) {
                const toast = new bootstrap.Toast(errorToast, { delay: 5000 });
                toast.show();
                console.log('Toast initialized for error:', errorToast.querySelector('.toast-body').textContent);
            }

</script>
@endif

</body>
</html>
