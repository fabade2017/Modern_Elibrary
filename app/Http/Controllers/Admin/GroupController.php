<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::paginate(10);
       //$classes = Classes::paginate(10); // 10 per page
        return view('admin.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|unique:groups']);
        Group::create($request->all());
        return redirect()->route('admin.groups.index')->with('success','Group created.');
    }

    public function edit(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }
  public function show(Group $group)
    {
        return view('admin.groups.edit', compact('group'));
    }

    public function update(Request $request, Group $group)
    {
        $request->validate(['name'=>'required|unique:groups,name,'.$group->id]);
        $group->update($request->all());
        return redirect()->route('admin.groups.index')->with('success','Group updated.');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return redirect()->route('admin.groups.index')->with('success','Group deleted.');
    }

    public function toggleActive(Group $group)
{
    $group->active = !$group->active;
    $group->save();
    return redirect()->back()->with('success', 'Group status updated.');
}

}
