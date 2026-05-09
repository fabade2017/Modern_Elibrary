<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();

    //Featured resources (latest 6)
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
            //   $featured = Resource::where('active', 1)
            //             ->where(function($query) use ($student) {
            //                 $query->whereHas('classes', function($q) use ($student) {
            //                         $q->where('classes.id', $student->class_id);
            //                     })
            //                     ->orWhereHas('groups', function($q) use ($student) {
            //                         $q->whereIn('groups.id', $student->groups->pluck('id'));
            //                     })
            //                     ->orWhereHas('roles', function($q) use ($student) {
            //                         $q->where('roles.id', $student->role_id);
            //                     });
            //             })
            //             ->latest()
            //              ->take(6)
            //         ->get();

    // Main resource query
    $query = Resource::where('active', true)
        ->whereHas('access', function ($q) use ($user) {
            $q->where(function ($sub) use ($user) {
                $sub->where('role_id', $user->role_id)
                    ->orWhere('class_id', $user->class_id)
                    ->orWhereIn('group_id', $user->groups->pluck('id'));
            })->where('active', true);
        });

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('type')) {
        $query->where('resource_type', $request->type);
    }

    $resources = $query->latest()->paginate(12);

    return view('student.dashboard', compact('resources','featured'));
}

    /*public function index(Request $request)
    {
        $user = Auth::user();

        // Base query: only active resources
        $query = Resource::where('active', true)
            ->whereHas('resourceAccesses', function ($q) use ($user) {
                $q->where(function ($sub) use ($user) {
                    $sub->where('role_id', $user->role_id)
                        ->orWhere('class_id', $user->class_id)
                        ->orWhereIn('group_id', $user->groups->pluck('id'));
                })->where('active', true);
            });

        // Filters
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('resource_type', $request->type);
        }

        $resources = $query->latest()->paginate(12);

        return view('student.dashboard', compact('resources'));
    }*/
}
