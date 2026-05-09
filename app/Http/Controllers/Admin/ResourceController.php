<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceAccess;
use App\Models\User;
use App\Models\Role;
use App\Models\ClassModel;
use App\Models\Group;
use App\Models\ItemCategory;
use App\Models\ResourceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::latest()->paginate(10);
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        $resourceType = ResourceType::all();
        $categories = ItemCategory::all();
        return view('admin.resources.create', compact('resourceType', 'categories'));
    }

   public function store(Request $request)
{
    Log::debug('Resource Store Request:', $request->all());

    if ($request->hasFile('file')) {
        Log::debug('File detected in request:', [
            'name' => $request->file('file')->getClientOriginalName(),
            'size' => $request->file('file')->getSize(),
            'mime' => $request->file('file')->getMimeType(),
        ]);
    } else {
        Log::warning('No file uploaded in request.');
    }

    $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png,gif,mp4', 'max:32768'],
    'category_id' => ['required', 'exists:categories,id'],
        'resource_type_id' => ['required', 'exists:resource_types,id'],
        /*   'downloadable' => ['sometimes','nullable', 'boolean'],
         'view_online' => ['nullable', 'boolean'],
        'active' => ['nullable', 'boolean']*/
      
    ]);

    // $filePath = null;
    // if ($request->hasFile('file')) {
    //     $filePath = $request->file('file')->store('resources', 'public');
    // }
$filePath = null;

if ($request->hasFile('file')) {
    $file = $request->file('file');

    // Log basic file details
    Log::debug('File upload detected:', [
        'name' => $file->getClientOriginalName(),
        'size' => $file->getSize(),
        'mime' => $file->getMimeType(),
    ]);

    try {
        // Attempt to store file
        $filePath = $file->store('resources', 'public');
Log::error('Nothing44444', [
            'error' => $filePath,
            'trace' => 'kkkkkk',
            'disk' => 'public',
            'folder' => 'resources',
]);
        if ($filePath) {
            Log::info('File successfully stored.', [
                'path' => $filePath,
                'disk' => 'public'
            ]);
        } else {
            Log::error('File store() returned null or empty path.', [
                'disk' => 'public',
                'folder' => 'resources',
            ]);
        }

    } catch (\Exception $e) {
        // Log storage failure
        Log::error('File storage failed.', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'disk' => 'public',
            'folder' => 'resources',
        ]);
    }
} else {
    Log::warning('No file found in request.');
}

    $resource = Resource::create([
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $filePath,
        'downloadable' => $request->boolean('downloadable', false),
        'view_online' => $request->boolean('view_online', false),
        'active' => $request->boolean('active', true),
        'category_id' => $request->category_id,
        'resource_type_id' => $request->resource_type_id,
    ]);

    // Attach access
    foreach ($request->role_ids ?? [] as $role_id) {
        ResourceAccess::create([
            'resource_id' => $resource->id,
            'role_id' => $role_id,
            'active' => true,
        ]);
    }
    foreach ($request->class_ids ?? [] as $class_id) {
        ResourceAccess::create([
            'resource_id' => $resource->id,
            'class_id' => $class_id,
            'active' => true,
        ]);
    }
    foreach ($request->group_ids ?? [] as $group_id) {
        ResourceAccess::create([
            'resource_id' => $resource->id,
            'group_id' => $group_id,
            'active' => true,
        ]);
    }

    return redirect()->route('admin.resources.index')
        ->with('success', 'Resource created successfully.');
}


    public function edit(Resource $resource)
    {
        $resourceType = ResourceType::all();
        $categories = ItemCategory::all();
        return view('admin.resources.edit', compact('resource', 'resourceType', 'categories'));
    }
   public function show(Resource $resource)
    {
        $resourceType = ResourceType::all();
        $categories = ItemCategory::all();
        return view('admin.resources.edit', compact('resource', 'resourceType', 'categories'));
    }

    public function update(Request $request, Resource $resource)
    {

        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
          'resource_type_id' => 'required|exists:resource_types,id',
            'category_id' => 'required|exists:categories,id',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf,mp4|max:32768',
             'downloadable' => 'boolean',
             'view_online' => 'boolean',
             'active' => 'boolean'
        ]);
     //return redirect()->route('admin.resources.index')->with('success', 'Resource updated Testing.');
        $filePath = $resource->file_path;
        if ($request->hasFile('file')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            try {
                $filePath = $request->file('file')->store('resources', 'public');
                Log::debug('File updated at:', ['path' => $filePath]);
            } catch (\Exception $e) {
                Log::error('Failed to update file:', [
                    'error' => $e->getMessage(),
                    'file' => $request->file('file')->getClientOriginalName(),
                ]);
                return redirect()->back()->withErrors(['file' => 'Failed to update file: ' . $e->getMessage()])->withInput();
            }
        }

        $resource->update([
            'title' => $request->title,
            'description' => $request->description,
            'resource_type_id' => $request->resource_type_id,
            'category_id' => $request->category_id,
            'file_path' => $filePath,
            'downloadable' => $request->boolean('downloadable'),
            'view_online' => $request->boolean('view_online'),
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('success', 'Resource deleted successfully.');
    }

    public function toggleActive(Resource $resource)
    {
        $resource->active = !$resource->active;
        $resource->save();
        return redirect()->back()->with('success', 'Resource status updated.');
    }

     public function toggleFeature(Resource $resource)
    {
        $resource->is_featured = !$resource->is_featured;
        $resource->save();
        return redirect()->back()->with('success', 'Feature status updated.');
    }
}