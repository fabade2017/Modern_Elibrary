<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class PrivilegeController extends Controller
{
    public function index()
    {
        $privileges = Privilege::paginate(10);
        return view('admin.privileges.index', compact('privileges'));
    }

    public function create()
    {
        return view('admin.privileges.create');
    }

    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required|unique:permissions,name',
            'slug' => 'required|unique:permissions,slug',
            'active' => 'required|boolean',
        ]);

        Privilege::create($request->all());
        return redirect()->route('admin.privileges.index')->with('success', 'Privilege created successfully.');
    }

    public function edit(Privilege $privilege)
    {
        return view('admin.privileges.edit', compact('privilege'));
    }
   public function show(Privilege $privilege)
    {
        return view('admin.privileges.edit', compact('privilege'));
    }
    public function update(Request $request, Privilege $privilege)
    {
        $request->validate(['name' => 'required|unique:privileges,name,'.$privilege->id]);
        $privilege->update($request->all());
        return redirect()->route('admin.privileges.index')->with('success', 'Privilege updated successfully.');
    }

    public function destroy(Privilege $privilege)
    {
        $privilege->delete();
        return redirect()->route('admin.privileges.index')->with('success', 'Privilege deleted.');
    }
}

