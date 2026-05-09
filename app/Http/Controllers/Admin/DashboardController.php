<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Group;
use App\Models\Category;
use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' =>  User::where('role_id',3)->count(),
            'active_users' => User::where('active',1)->count(), //StudentData::whereNotNull('email')->count(), // Example logic
            'classes' => DB::table('classes')->count(),
            'groups' => DB::table('groups')->count(),
            'categories' => DB::table('categories')->count(),
            'resources' => DB::table('resources')->count(),
            'active_resources' => DB::table('resources')->where('active', true)->count(),
            'resourceTypeData' => DB::table('resource_types')
                ->select('name as name', DB::raw('count(*) as resources_count'))
                ->groupBy('name')
                ->get(),
            'userRoleData' => User::select('role', DB::raw('count(*) as user_count'))
                ->groupBy('role')
                ->get(),
            'classDepartmentData' => DB::table('classes')
                ->select('name', DB::raw('count(*) as class_count'))
                ->groupBy('name')
                ->get(),
            'groupCategoryData' => DB::table('categories')
                ->select('name', DB::raw('count(*) as group_count'))
                ->groupBy('name')
                ->get(),
        ];
$settings = [
            'site_logo' => DB::table('settings')->where('key', 'site_logo')->value('value'),
        ];
        return view('admin.dashboard', compact('stats','settings'));
    }
    public function indexRR()
    {
        $stats = [
            'total_users'     => User::where('role_id',3)->count(),
            'active_users'    => User::where('active',1)->count(),
            'classes'         => Classes::where('active',1)->count(),
            'groups'          => Group::where('active',1)->count(),
            'categories'      => Category::where('active',1)->count(),
            'resources'       => Resource::count(),
            'active_resources'=> Resource::where('active',1)->count(),
             'resourceTypeData' => ResourceType::withCount('resources')->get(),
        ];

        // For charts
       // $resourceTypeData = ResourceType::withCount('resources')->get();,'resourceTypeData'

        return view('admin.dashboard', compact('stats'));
    }
}
