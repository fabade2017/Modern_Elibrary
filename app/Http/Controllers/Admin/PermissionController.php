<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
            'slug' => 'required|unique:permissions,slug',
            'active' => 'required|boolean',
        ]);

        Permission::create($request->all());
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function show(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
            'slug' => 'required|unique:permissions,slug,' . $permission->id,
            'active' => 'required|boolean',
        ]);

        $permission->update($request->all());
        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->roles()->detach();
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted successfully.');
    }
           public function toggleActive(Permission $permission)
{
    $permission->active = !$permission->active;
    $permission->save();
    return redirect()->back()->with('success', 'Group status updated.');
}
}
