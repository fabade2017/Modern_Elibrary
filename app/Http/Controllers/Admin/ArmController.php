<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arm;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
class ArmController extends Controller
{
    public function index()
    {
       // $arms = Arm::all();
        $arms = Arm::paginate(10); // 10 per page

        return view('admin.arms.index', compact('arms'));
    }

    public function create()
    {
        return view('admin.arms.create');
    }

    public function store(Request $request)
    {
        //$request->validate(['name'=>'required|unique:arms']);
         $request->validate([
        'name' => 'required|unique:arms',
        'description' => 'nullable|string',
        'active' => 'boolean'
    ]);
$request->description = $request->description ." - ".$request->active;
       Arm::create($request->all());
      //   Arm::create($data);
        return redirect()->route('arms.index')->with('success','Arm created.');
    }

    public function edit(Arm $arm)
    {
        return view('admin.arms.edit', compact('arm'));
    }
 public function show(Arm $arm)
    {
        return view('admin.arms.edit', compact('arm'));
    }
    public function update(Request $request, Arm $arm)
    {
        $request->validate(['name'=>'required|unique:arms,name,'.$arm->id]);
        $arm->update($request->all());
        return redirect()->route('arms.index')->with('success','Arm updated.');
    }

    public function destroy(Arm $arm)
    {
        $arm->delete();
        return redirect()->route('arms.index')->with('success','Arm deleted.');
    }

    public function toggleActive(Arm $arm)
{
    $arm->active = !$arm->active;
    $arm->save();
    return redirect()->back()->with('success', 'Arm status updated.');
}

}
