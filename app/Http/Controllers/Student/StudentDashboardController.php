<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\ResourceAccess;
use App\Models\ItemCategory;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function indexGGGGGG()
    {
     
        $student = Auth::user();
                $user = Auth::user();
                $resourceType = ResourceType::where('active', true);
                        // Get resources by class, group, or role
        $resources = ResourceAccess::with('resource')->
            where(function($query) use ($student) {
                $query->whereHas('classes', function($q) use ($student) {
                        $q->where('classes.id', $student->class_id);
                    })
                    ->orWhereHas('group', function($q) use ($student) {
                        $q->whereIn('groups.id', $student->groups->pluck('id'));
                    })
                    ->orWhereHas('roles', function($q) use ($student) {
                        $q->where('roles.id', $student->role_id);
                    });
            })
            ->latest()
            ->paginate(12);

        $categories = Category::where('active',1)->get();
              $featured = Resource::where('active', true)
        ->where('is_featured', true)
        ->whereHas('access', function ($q) use ($user) {
            $q->where(function ($sub) use ($user) {
                $sub->where('role_id', $user->role_id)
                    ->orWhere('class_id', $user->class_id)
                    ->orWhereIn('group_id', $user->groups->pluck('id'));
            })->where('active', true);
        })
        ->latest()
        ->take(6)
        ->get();

          $featured = Resource::where('active', true)
        ->where('is_featured', true)
        ->whereHas('access', function ($q) use ($student) {
            $q->where(function ($sub) use ($student) {
                $sub->where('role_id', $student->role_id)
                    ->orWhere('class_id', $student->class_id)
                    ->orWhereIn('group_id', $student->groups->pluck('id'));
            })->where('active', true);
        })
        ->latest()
        ->take(6)
        ->get();
              $point = 0;
        return view('student.dashboard', compact('resources','categories','featured','resourceType','point'));
    }
 public function showGGGGGGG(Request $request)
    {
        $point = 0;
        if($request->category_id > 0){$point = $request->category_id;}
        $student = Auth::user();
            $user = Auth::user();
            $resourceType = ResourceType::where('active', true);
                    // Get resources by class, group, or role
        $resources = ResourceAccess::with('resource')->
            where(function($query) use ($student) {
                $query->whereHas('classes', function($q) use ($student) {
                        $q->where('classes.id', $student->class_id);
                    })
                    ->orWhereHas('group', function($q) use ($student) {
                        $q->whereIn('groups.id', $student->groups->pluck('id'));
                    })
                      ->orWhereHas('category', function($q) use ($student) {
                        $q->whereIn('category.id', $student->category->pluck('id'));
                    })
                    ->orWhereHas('roles', function($q) use ($student) {
                        $q->where('roles.id', $student->role_id);
                    });
            })
            ->latest()
            ->paginate(12);

        $categories = Category::where('active',1)->get();
              $featured = Resource::where('active', true)
        ->where('is_featured', true)
        ->whereHas('access', function ($q) use ($user) {
            $q->where(function ($sub) use ($user) {
                $sub->where('role_id', $user->role_id)
                    ->orWhere('class_id', $user->class_id)
                    ->orWhereIn('group_id', $user->groups->pluck('id'));
            })->where('active', true);
        })
        ->latest()
        ->take(6)
        ->get();
             $itemCategories = ItemCategory::where('active',1)->get();
        //       $featured = Resource::where('active', true)
        // ->where('is_featured', true)
        // ->whereHas('access', function ($q) use ($user) {
        //     $q->where(function ($sub) use ($user) {
        //         $sub->where('role_id', $user->role_id)
        //             ->orWhere('class_id', $user->class_id)
        //             ->orWhereIn('group_id', $user->groups->pluck('id'));
        //     })->where('active', true);
        // })
        // ->latest()
        // ->take(6)
        // ->get();
          $featured = Resource::where('active', true)
        ->where('is_featured', true)
        ->whereHas('access', function ($q) use ($student) {
            $q->where(function ($sub) use ($student) {
                $sub->where('role_id', $student->role_id)
                    ->orWhere('class_id', $student->class_id)
                    ->orWhereIn('group_id', $student->groups->pluck('id'));
            })->where('active', true);
        })
        ->latest()
        ->take(6)
        ->get();
        return view('student.dashboard', compact('resources','categories','featured','resourceType','point'));
    }
    
public function index(Request $request)
    {
        $student = Auth::user();
        if (!$student ) {
            return redirect()->route('login');
        }
        if($student->actve==0){
            return response()->view('errors.not-activated', [], 403);

        }
       $topUsers = User::orderBy('points', 'desc')
                        ->take(5)
                        ->get();

       // return view('student.leaderboard', compact('topUsers'));

        // Build the base ResourceAccess query
        $resourceQuery = ResourceAccess::with(['resource', 'resource.type'])
            ->where(function ($query) use ($student) {
                $query->whereHas('classes', function ($q) use ($student) {
                    $q->where('classes.id', $student->class_id);
                })
                ->orWhereHas('group', function ($q) use ($student) {
                    $q->whereIn('groups.id', $student->groups->pluck('id'));
                })
                ->orWhereHas('roles', function ($q) use ($student) {
                    $q->where('roles.id', $student->role_id);
                });
            });
// $search = $request instanceof \Illuminate\Http\Request ? $request->input('search', '') : '';
// if ($search) {
//     // Process search input
// }

// $categoryId = $request instanceof \Illuminate\Http\Request ? $request->input('category_id', null) : null;
// if ($categoryId) {
//     // Process category_id input
// }
        // Apply search filter
        $search = $request instanceof \Illuminate\Http\Request ? $request->input('search', '') : '';
if ($search) {
      //  if ($request->filled('search')) {
            $resourceQuery->whereHas('resource', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Apply category filter
       // if ($request->filled('category_id')) {
       $categoryId = $request instanceof \Illuminate\Http\Request ? $request->input('category_id', null) : null;
if ($categoryId) {
            $resourceQuery->whereHas('resource', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Paginate resources
        $resources = $resourceQuery->latest()->paginate(12);

        // Build the featured resources query
        $featuredQuery = Resource::where('active', true)
            ->where('is_featured', true)
            ->whereHas('access', function ($q) use ($student) {
                $q->where(function ($sub) use ($student) {
                    $sub->where('role_id', $student->role_id)
                        ->orWhere('class_id', $student->class_id)
                        ->orWhereIn('group_id', $student->groups->pluck('id'));
                })->where('active', true);
            });

        // Apply search filter to featured
        if ($request->filled('search')) {
            $featuredQuery->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Apply category filter to featured
        if ($request->filled('category_id')) {
            $featuredQuery->where('category_id', $request->category_id);
        }

        // Get featured resources
        $featured = $featuredQuery->with('resourceType')->latest()->take(6)->get();

        // Get categories and resource types
        $categories = Category::where('active', 1)->get();
        $resourceTypes = ResourceType::where('active', true)->get();

        return view('student.dashboard', compact('resources', 'categories', 'featured', 'resourceTypes','topUsers'));
    }

    public function showResource($id)
    {
         $student = Auth::user();
                $user = Auth::user();
        $resource = Resource::with('type')->findOrFail($id);

        if (!$resource->active) {
            abort(403, 'This resource is not available.');
        }

        // Optional: Verify access
        $hasAccess = ResourceAccess::where('resource_id', $id)
            ->where(function ($query) use ($student) {
                $query->whereHas('classes', function ($q) use ($student) {
                    $q->where('classes.id', $student->class_id);
                })
                ->orWhereHas('group', function ($q) use ($student) {
                    $q->whereIn('groups.id', $student->groups->pluck('id'));
                })
                ->orWhereHas('roles', function ($q) use ($student) {
                    $q->where('roles.id', $student->role_id);
                });
            })->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this resource.');
        }

        return view('student.resource', compact('resource'));
    }

    public function showResourceGGG($id)
    {
        $resource = Resource::findOrFail($id);

        if (!$resource->active) {
            abort(403, 'This resource is not available.');
        }

        return view('student.resource', compact('resource'));
    }
}
