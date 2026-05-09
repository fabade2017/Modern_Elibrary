<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">eLibrary Admin</a>
    <div>
      <ul class="navbar-nav me-auto">
       

   <li class="nav-item"><a class="nav-link" href="{{ route('admin.categories.index') }}">Categories</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.classes.index') }}">Classes</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.groups.index') }}">Groups</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.roles.index') }}">Roles</a></li>
     
      
        <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="nav-link btn btn-link text-white">
              <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
          </form>
</li>
      </ul>
       
    </div>
  </div>
</nav>
