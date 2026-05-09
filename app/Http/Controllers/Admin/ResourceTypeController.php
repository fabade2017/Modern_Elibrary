<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class ResourceTypeController extends Controller
{
    public function index()
    {
        $resourceTypes = ResourceType::paginate(10);
        return view('admin.resource_types.index', compact('resourceTypes'));
    }

    public function create()
    {
        return view('admin.resource_types.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|unique:resource_types']);
        ResourceType::create($request->all());
        return redirect()->route('admin.resource_types.index')->with('success','Resource type created.');
    }

    public function edit(ResourceType $resourceType)
    {
        return view('admin.resource_types.edit', compact('resourceType'));
    }
  public function show(ResourceType $resourceType)
    {
        return view('admin.resource_types.edit', compact('resourceType'));
    }
    public function update(Request $request, ResourceType $resourceType)
    {
        $request->validate(['name'=>'required|unique:resource_types,name,'.$resourceType->id]);
        $resourceType->update($request->all());
        return redirect()->route('admin.resource_types.index')->with('success','Resource type updated.');
    }

    public function destroy(ResourceType $resourceType)
    {
        $resourceType->delete();
        return redirect()->route('admin.resource_types.index')->with('success','Resource type deleted.');
    }
            public function toggleActive(ResourceType $resourceType)
{
    $resourceType->active = !$resourceType->active;
    $resourceType->save();
    return redirect()->back()->with('success', 'Group status updated.');
}
}

