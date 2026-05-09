<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="{{ route('student.dashboard') }}">eLibrary Student</a>
    <div>
      <ul class="navbar-nav me-auto">
     
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
