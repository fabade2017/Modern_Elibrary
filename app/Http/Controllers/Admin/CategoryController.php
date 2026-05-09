<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
class CategoryController extends Controller
{
    public function index()
    {
       // $categories = Category::all();
        $categories = Category::paginate(10); // 10 per page

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        //$request->validate(['name'=>'required|unique:categories']);
         $request->validate([
        'name' => 'required|unique:categories',
        'description' => 'nullable|string',
        'active' => 'boolean'
    ]);
$request->description = $request->description ." - ".$request->active;
       Category::create($request->all());
      //   Category::create($data);
        return redirect()->route('categories.index')->with('success','Category created.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
 public function show(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        $request->validate(['name'=>'required|unique:categories,name,'.$category->id]);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success','Category updated.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted.');
    }

    public function toggleActive(Category $category)
{
    $category->active = !$category->active;
    $category->save();
    return redirect()->back()->with('success', 'Category status updated.');
}

}
