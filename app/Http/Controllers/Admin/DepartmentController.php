<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
class DepartmentController extends Controller
{
    public function index()
    {
       // $departments = Department::all();
        $departments = Department::paginate(10); // 10 per page

        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        //$request->validate(['name'=>'required|unique:departments']);
         $request->validate([
        'name' => 'required|unique:departments',
        'description' => 'nullable|string',
        'active' => 'boolean'
    ]);
$request->description = $request->description ." - ".$request->active;
       Department::create($request->all());
      //   Department::create($data);
        return redirect()->route('departments.index')->with('success','Department created.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }
 public function show(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }
    public function update(Request $request, Department $department)
    {
        $request->validate(['name'=>'required|unique:departments,name,'.$department->id]);
        $department->update($request->all());
        return redirect()->route('departments.index')->with('success','Department updated.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success','Department deleted.');
    }

    public function toggleActive(Department $department)
{
    $department->active = !$department->active;
    $department->save();
    return redirect()->back()->with('success', 'Department status updated.');
}

}
