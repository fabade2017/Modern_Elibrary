@extends('layouts.admin')

@section('content')
<div class="container">
    <h1  class="mb-4">School Admin Mappings </h1>
@if(Auth::user()->role_id === 1)
    <a href="{{ route('school_admin_mappers.create') }}" class="btn btn-primary mb-3">+Add Mapping</a><a href="{{ route('school_admin_mappers.download', ['search' => request('search')]) }}" 
   class="btn btn-success mb-3"  style="float: right;">Download CSV</a>
@endif
<form method="GET" action="{{ route('school_admin_mappers.index') }}" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by school, admin email...">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>


    <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>School</th>
            <th>Admin Email</th>
            <th>School</th>
            <th>Student Count</th>
            <th>Pics</th>
    @if(Auth::user()->role_id === 1)        <th>Active</th>
            <th>Action</th>@endif
        </tr>
    </thead>
    <tbody>
        @foreach($mappings as $schoolAdminMapper)
        <tr>
            <td>{{ $schoolAdminMapper->schoolData?->name }}</td>
            <td>
                <a href="javascript:void(0);" 
                   class="show-user-details" data-pic="{{ $schoolAdminMapper->others }}"
                   data-email="{{ $schoolAdminMapper->adminemail }}">
                   {{ $schoolAdminMapper->adminemail }}
                </a>
            </td>
            <td>{{ $schoolAdminMapper->school }}</td>
            <td>{{ $schoolAdminMapper->studentcount }}</td>
            <!-- <td>{{ $schoolAdminMapper->others }}</td> -->
            <td>
    @if($schoolAdminMapper->others)
        <img src="{{ asset('storage' . $schoolAdminMapper->others) }}" width="80" alt="Mapping Image">
    @else
        <span>No Image</span>
    @endif
</td>
         @if(Auth::user()->role_id === 1)   <td>
                 <form action="{{ route('schoolAdminMapper.toggle-active',$schoolAdminMapper) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $schoolAdminMapper->active ? 'btn-success':'btn-secondary' }}">
                                {{ $schoolAdminMapper->active == 1 ? 'Active':'Inactive' }}
                            </button>
                        </form>
</td><td>
<a href="{{ route('school_admin_mappers.edit', $schoolAdminMapper) }}" class="btn btn-sm btn-warning">
        Edit
    </a>

    <form action="{{ route('school_admin_mappers.destroy', $schoolAdminMapper->id) }}" 
          method="POST" 
          style="display:inline-block;" 
          onsubmit="return confirm('Are you sure you want to delete this mapping?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form></td>@endif
        </tr>
        @endforeach
    </tbody>
</table>

<!-- User Details Modal -->
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">User Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
      <table><td>  <p><strong>Name:</strong> <span id="userName"></span></p>
        <p><strong>Email:</strong> <span id="userEmail"></span></p>
        <p><strong>Role:</strong> <span id="userRole"></span></p>
        <p><strong>Created At:</strong> <span id="userCreated"></span></p>
        <p><strong>Updated At:</strong> <span id="userUpdated"></span></p></td>
        <td>
          <img id="userPassport" src="" alt="User Passport" style="max-width:150px; max-height:150px;">

</td>
</table>
      </div>
    </div>
  </div>
</div>


    {{ $mappings->links() }}
</div>
@endsection

@section('script')

    <script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.show-user-details').forEach(function(el) {
        el.addEventListener('click', function() {
            let email = this.dataset.email;
            let pics = this.dataset.pic;
            // use Blade route helper, then append email
        //    let url = "{{ route('school_admin_mappers.user.details', ':email') }}";
            let url = "{{ route('school_admin_mappers.user.details') }}" + "?email=" + encodeURIComponent(email);

       //     url = url.replace(':email', encodeURIComponent(email));
alert(url);
            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error('User not found');
                    return res.json();
                })
                .then(user => {
                    if (user.error) {
                        alert(user.error);
                        return;
                    }
                    document.getElementById('userName').innerText = user.name ?? '';
                    document.getElementById('userEmail').innerText = user.email ?? '';
                    document.getElementById('userRole').innerText = user.role ?? '';
                    document.getElementById('userCreated').innerText = user.created_at ?? '';
                    document.getElementById('userUpdated').innerText = user.updated_at ?? '';
                    document.getElementById('userPassport').src = '../storage'+pics ?? 'default.png'; // fallback if empty

                    let modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                    modal.show();
                })
                .catch(err => console.error(err));
        });
    });
});
 /*
document.addEventListener('DOMContentLoaded', function () {
 
    document.querySelectorAll('.show-user-details').forEach(function(el) {
        el.addEventListener('click', function() {
            let email = this.dataset.email;
   alert('Get in Here' + email);
      fetch(`/school_admin_mappers/user-details?email=${encodeURIComponent(email)}`)
                .then(res => res.json())
                .then(user => {
                    if (user.error) {
                        alert(user.error);
                        return;
                    }
                    document.getElementById('userName').innerText = user.name ?? '';
                    document.getElementById('userEmail').innerText = user.email ?? '';
                    document.getElementById('userRole').innerText = user.role ?? '';
                    document.getElementById('userCreated').innerText = user.created_at ?? '';
                    document.getElementById('userUpdated').innerText = user.updated_at ?? '';

                    let modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                    modal.show();
                })
                .catch(err => console.error(err));
        });
    });
});*/
</script>

   

@endsection
