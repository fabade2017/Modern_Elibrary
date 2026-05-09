<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
class ClassesController extends Controller
{
    public function index()
    {
       // $classes = Classes::all();
        $classes = Classes::paginate(10); // 10 per page
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:classes']);
        Classes::create($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(Classes $class)
    {
        return view('admin.classes.edit', compact('class'));
    }
 public function show(Classes $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate(['name' => 'required|unique:classes,name,'.$class->id]);
        $class->update($request->all());
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted.');
    }

    public function toggleActive(Classes $class)
{
    $class->active = !$class->active;
    $class->save();
    return redirect()->back()->with('success', 'Class status updated.');
}

}

