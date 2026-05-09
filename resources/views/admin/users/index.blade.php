
@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="container">
    <h1 class="mb-4">Everyone in the eLibrary {{ $subdomain }}</h1>
    @auth
        @if(Auth::user()->role_id === 1)
            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                + Add User
            </button>
            <a href="{{ route('school_admin_mappers.download', ['search' => request('search')]) }}" 
               class="btn btn-success mb-3" style="float: right;">Download CSV</a>
        @endif
    @endauth
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by school, email...">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Class</th>
                <th>Department</th>
                <th>Category</th>
                <th>Arm</th>
                <th>Group</th>
                <th>Active</th>
                @if(Auth::user()->role_id === 1)
                    <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="user-row" data-id="{{ $user->id }}">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role_id ? $user->role : '—' }}</td>
                    <td>{{ $user->classes ? $user->classes->name : '—' }}</td>
                    <td>{{ $user->department ? $user->department->name : '—' }}</td>
                    <td>{{ $user->category ? $user->category->name : '—' }}</td>
                    <td>{{ $user->arm ? $user->arm->name : '—' }}</td>
                    <td>
                        <ul>
                            @foreach($user->groups as $group)
                                <li>{{ $group->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form action="{{ route('users.toggle-active', $user->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $user->active ? 'btn-success' : 'btn-secondary' }}">
                                {{ $user->active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    @if(Auth::user()->role_id === 1)
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-role_id="{{ $user->role_id }}"
                                data-class_id="{{ $user->class_id }}"
                                data-department_id="{{ $user->department_id }}"
                                data-arm_id="{{ $user->arm_id }}"
                                data-groups="{{ json_encode($user->groups->pluck('id')->toArray()) }}"
                                data-active="{{ $user->active ? 1 : 0 }}"
                                data-action="{{ route('admin.users.update', $user->id) }}">
                                Edit
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ Auth::user()->role_id === 1 ? 10 : 9 }}">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->links() }}

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                <option value="">— Select Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <select name="class_id" class="form-control @error('class_id') is-invalid @enderror">
                                <option value="">— None —</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" @selected(old('class_id') == $class->id)>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror">
                                <option value="">— None —</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Arm</label>
                            <select name="arm_id" class="form-control @error('arm_id') is-invalid @enderror">
                                <option value="">— None —</option>
                                @foreach($arms as $arm)
                                    <option value="{{ $arm->id }}" @selected(old('arm_id') == $arm->id)>
                                        {{ $arm->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('arm_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Groups</label>
                            <select name="groups[]" class="form-control @error('groups') is-invalid @enderror" multiple>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" @selected(collect(old('groups'))->contains($group->id))>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('groups')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="active" value="1" class="form-check-input" id="active" @checked(old('active', 1))>
                            <label for="active" class="form-check-label">Active</label>
                        </div>
                        <button type="submit" class="btn btn-success">Save User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_user_id" name="id">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="edit_name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="edit_email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="edit_password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" id="edit_role_id">
                                <option value="">— Select Role —</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Class</label>
                            <select name="class_id" class="form-control @error('class_id') is-invalid @enderror" id="edit_class_id">
                                <option value="">— None —</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" id="edit_department_id">
                                <option value="">— None —</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Arm</label>
                            <select name="arm_id" class="form-control @error('arm_id') is-invalid @enderror" id="edit_arm_id">
                                <option value="">— None —</option>
                                @foreach($arms as $arm)
                                    <option value="{{ $arm->id }}">{{ $arm->name }}</option>
                                @endforeach
                            </select>
                            @error('arm_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Groups</label>
                            <select name="groups[]" class="form-control @error('groups') is-invalid @enderror" id="edit_groups" multiple>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            @error('groups')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="active" value="1" class="form-check-input" id="edit_active">
                            <label for="edit_active" class="form-check-label">Active</label>
                        </div>
                        <button type="submit" class="btn btn-success">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tooltip container -->
    <div id="user-tooltip" class="card shadow-sm p-2 position-absolute d-none" style="width: 250px; z-index: 1000;">
        <div id="tooltip-content">Loading...</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tooltip = document.getElementById("user-tooltip");
    const tooltipContent = document.getElementById("tooltip-content");

    document.querySelectorAll(".user-row").forEach(row => {
        row.addEventListener("mouseenter", function (e) {
            let userId = this.dataset.id;
            fetch(`/user/details/${userId}`)
                .then(res => res.json())
                .then(data => {
                    tooltipContent.innerHTML = `
                        <div class="card shadow-lg border-0" style="width: 18rem;">
                            <div class="card-header bg-primary text-white text-center">
                                <h5 class="mb-0">User ${userId}</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Surname:</strong> ${data.surname ?? 'N/A'}</p>
                                <p class="mb-1"><strong>Firstname:</strong> ${data.firstname ?? 'N/A'}</p>
                                <p class="mb-1"><strong>Othername:</strong> ${data.othername ?? 'N/A'}</p>
                                <p class="mb-1"><strong>Admission No:</strong> ${data.admission_number ?? 'N/A'}</p>
                                <p class="mb-1"><strong>Arm:</strong> ${data.arm ?? 'N/A'}</p>
                                <p class="mb-1"><strong>Category:</strong> ${data.category ?? 'N/A'}</p>
                            </div>
                        </div>
                    `;
                    const rect = this.getBoundingClientRect();
                    tooltip.style.top = (rect.top + window.scrollY + 20) + "px";
                    tooltip.style.left = (rect.left + window.scrollX + 150) + "px";
                    tooltip.classList.remove("d-none");
                });
        });

        row.addEventListener("mouseleave", function () {
            tooltip.classList.add("d-none");
        });
    });

    const editModal = document.getElementById('editUserModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const role_id = button.getAttribute('data-role_id');
        const class_id = button.getAttribute('data-class_id');
        const department_id = button.getAttribute('data-department_id');
        const arm_id = button.getAttribute('data-arm_id');
        const groups = JSON.parse(button.getAttribute('data-groups'));
        const active = button.getAttribute('data-active') === '1';
        const action = button.getAttribute('data-action');

        const form = document.getElementById('editUserForm');
        form.action = action;
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_role_id').value = role_id || '';
        document.getElementById('edit_class_id').value = class_id || '';
        document.getElementById('edit_department_id').value = department_id || '';
        document.getElementById('edit_arm_id').value = arm_id || '';
        const groupSelect = document.getElementById('edit_groups');
        Array.from(groupSelect.options).forEach(option => {
            option.selected = groups.includes(parseInt(option.value));
        });
        document.getElementById('edit_active').checked = active;
        document.getElementById('edit_password').value = '';
    });
});
</script>
@endsection
