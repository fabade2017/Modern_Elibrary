<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceAccess;
use App\Models\User;
use App\Models\Role;
use App\Models\Arm;
use App\Models\Classes;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ResourceType;
use Illuminate\Database\Eloquent\Collection;
/*namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ResourceAccess;
use App\Models\User;
use App\Models\Role;
use App\Models\ClassModel;
use App\Models\Group;
use App\Models\Category;
use Illuminate\Http\Request;
*/
class ResourceAccessController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }

    public function index(Request $request)
    {
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $arm_id = $request->input('arm_id');
        $resource_type_id = $request->input('resource_type_id');

        $query = Resource::with(['itemCategory', 'type']);
        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('itemCategory', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  }) ->orWhereHas('arms', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('resourceType', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

         if ($arm_id) {
            $query->where('arm_id', $category_id);
        }
        if ($resource_type_id) {
            $query->where('resource_type_id', $resource_type_id);
        }

        $resources = $query->get();
        $users = User::with(['role', 'classes'])->get();
        $roles = Role::all();
        $classes = Classes::all();
        $groups = Group::all();
         $arms = Arm::all();
        $categories = Category::all();
        $resource_types = ResourceType::all();
        $resourceAccesses = ResourceAccess::with(['user', 'resource', 'roles', 'classes', 'group', 'arms'])->paginate(10);

        return view('admin.resource-access.index', compact('resources', 'users', 'roles', 'classes', 'groups', 'arms', 'categories', 'resource_types', 'resourceAccesses', 'search', 'category_id', 'resource_type_id'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id !== 1 && auth()->user()->role_id !== 2) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'resource_ids' => 'nullable|array',
            'resource_ids.*' => 'exists:resources,id',
            'role_id' => 'nullable|exists:roles,id',
             'arm_id' => 'nullable|exists:arms,id',
            'class_id' => 'nullable|exists:classes,id',
            'group_id' => 'nullable|exists:groups,id',
        ]);

        $data = $request->only(['user_id', 'role_id', 'class_id', 'group_id', 'arm_id', 'category_id']);
        $resourceIds = $request->resource_ids ?? [];

        if (empty($resourceIds) && !$request->role_id && !$request->class_id && !$request->group_id) {
            // Grant access to all resources
            ResourceAccess::create(['user_id' => $request->user_id]);
        } else {
            foreach ($resourceIds as $resourceId) {
                ResourceAccess::create(array_merge($data, ['resource_id' => $resourceId]));
            }
        }

        return redirect()->route('admin.resource-access.index')->with('success', 'Access assigned successfully.');
    }

    public function destroy(ResourceAccess $resourceAccess)
    {
        if (auth()->user()->role_id !== Role::where('name', 'superadmin')->first()->id) {
            abort(403);
        }

        $resourceAccess->delete();
        return redirect()->route('admin.resource-access.index')->with('success', 'Access removed successfully.');
    }
}
// class ResourceAccessController extends Controller
// {
//     // public function __construct()
//     // {
//     //     $this->middleware(['auth']);
//     // }

//     public function index()
//     {
//         // Only allow admins
//         if (auth()->user()->role_id !== 1) {
//             abort(403);
//         }

//         $resources = Resource::with(['category', 'resourceType'])->get();
//         $users = User::with(['role', 'classes'])->get();
//         $roles = Role::all();
//         $classes = Classes::all();
//         $groups = Group::all();
//         $categories = Category::all();
//         $resourceAccesses = ResourceAccess::with(['user', 'resource', 'role', 'classes', 'group'])->get();

//         return view('admin.resource-access.index', compact('resources', 'users', 'roles', 'classes', 'groups', 'categories', 'resourceAccesses'));
//     }

//     public function store(Request $request)
//     {
//         if (auth()->user()->role_id !== Role::where('name', 'admin')->first()->id) {
//             abort(403);
//         }

//         $request->validate([
//             'user_id' => 'required|exists:users,id',
//             'resource_id' => 'nullable|exists:resources,id',
//             'role_id' => 'nullable|exists:roles,id',
//             'class_id' => 'nullable|exists:classes,id',
//             'group_id' => 'nullable|exists:groups,id',
//             'category_id' => 'nullable|exists:categories,id',
//         ]);

//         // Ensure at least one access criterion is provided (or none for "access to all")
//         if (!$request->resource_id && !$request->role_id && !$request->class_id && !$request->group_id && !$request->category_id) {
//             $data = ['user_id' => $request->user_id]; // "Access to all"
//         } else {
//             $data = $request->only(['user_id', 'resource_id', 'role_id', 'class_id', 'group_id']);
//         }

//         ResourceAccess::create($data);

//         return redirect()->route('admin.resource-access.index')->with('success', 'Access assigned successfully.');
//     }

//     public function destroy(ResourceAccess $resourceAccess)
//     {
//         if (auth()->user()->role_id !== Role::where('name', 'admin')->first()->id) {
//             abort(403);
//         }

//         $resourceAccess->delete();
//         return redirect()->route('admin.resource-access.index')->with('success', 'Access removed successfully.');
//     }

    
// }