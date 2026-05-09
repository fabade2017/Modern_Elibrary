<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
class ItemCategoryController extends Controller
{
    public function index()
    {
       // $item_categories = ItemCategory::all();
        $item_categories = ItemCategory::paginate(10); // 10 per page

        return view('admin.item_categories.index', compact('item_categories'));
    }

    public function create()
    {
        return view('admin.item_categories.create');
    }

    public function store(Request $request)
    {
        //$request->validate(['name'=>'required|unique:item_categories']);
         $request->validate([
        'name' => 'required|unique:item_category',
        'description' => 'nullable|string',
        'active' => 'boolean'
    ]);
$request->description = $request->description ." - ".$request->active;
       ItemCategory::create($request->all());
      //   ItemCategory::create($data);
        return redirect()->route('item_categories.index')->with('success','ItemCategory created.');
    }

    public function edit(ItemCategory $item_category)
    {
        return view('admin.item_categories.edit', compact('item_category'));
    }
 public function show(ItemCategory $item_category)
    {
        return view('admin.item_categories.edit', compact('item_category'));
    }
    public function update(Request $request, ItemCategory $item_category)
    {
        $request->validate(['name'=>'required|unique:item_category,name,'.$item_category->id]);
        $item_category->update($request->all());
        return redirect()->route('item_categories.index')->with('success','ItemCategory updated.');
    }

    public function destroy(ItemCategory $item_category)
    {
        $item_category->delete();
        return redirect()->route('item_categories.index')->with('success','ItemCategory deleted.');
    }

    public function toggleActive(ItemCategory $item_category)
{
    $item_category->active = !$item_category->active;
    $item_category->save();
    return redirect()->back()->with('success', 'ItemCategory status updated.');
}

}
