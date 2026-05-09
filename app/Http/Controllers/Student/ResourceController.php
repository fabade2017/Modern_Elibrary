<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get resources available by class, group, or general
        $resources = Resource::whereHas('access', function($query) use ($user) {
            $query->where('class_id', $user->class_id)
                  ->orWhereIn('group_id', $user->groups->pluck('id'))
                  ->orWhere('user_id', $user->id)
                  ->orWhereNull('class_id')->whereNull('group_id')->whereNull('user_id');
        })->where('active', true)->get();

        return view('student.resources.index', compact('resources'));
    }

    public function show($id)
    {
        $resource = Resource::findOrFail($id);
        return view('student.resources.show', compact('resource'));
    }
}
