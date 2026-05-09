<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\Group;
use App\Models\Category;
use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
         $adminId = Auth::user();
        if($adminId->role_id != 1 && $adminId->role_id != 2)
        {
           return view('welcome');
        }
        if($adminId->role_id === 1){
        $stats = [
            'total_users' =>  User::where('role_id',3)->count(),
            'active_users' => User::where('active',1)->where('role_id',3)->count(), //StudentData::whereNotNull('email')->count(), // Example logic
            'classes' => DB::table('classes')->count(),
            'groups' => DB::table('groups')->count(),
            'categories' => DB::table('categories')->count(),
            'resources' => DB::table('resources')->count(),
            'schools' => DB::table('schools')->count(),
             'arms' => DB::table('arms')->count(),
            'departments' => DB::table('departments')->count(),
             'active_departments' => DB::table('departments')->where('active', true)->count(),
            'active_arms' => DB::table('arms')->where('active', true)->count(),
            'active_resources' => DB::table('resources')->where('active', true)->count(),
            'resourceTypeData' => DB::table('resource_types')
                ->select('name as name', DB::raw('count(*) as resources_count'))
                ->groupBy('name')
                ->get(),
            'userRoleData' => User::select('role', DB::raw('count(*) as user_count'))
                ->groupBy('role')
                ->get(),
                   'userClassData' => User::select('classes.name', DB::raw('count(*) as user_count'))
                     ->join('classes', 'users.class_id', '=', 'classes.id')
                ->groupBy('classes.name')
                ->get(),
              'userDepartmentData' => User::select('departments.name', DB::raw('count(*) as user_count'))
    ->join('departments', 'users.department_id', '=', 'departments.id')
    ->groupBy('departments.name')
    ->get(), 
     'userCategoryData' => User::select('categories.name', DB::raw('count(*) as user_count'))
    ->join('categories', 'users.category_id', '=', 'categories.id')
    ->groupBy('categories.name')
    ->get(),
    'userGroupsData' => User::select('groups.name', DB::raw('count(*) as user_count'))
    ->join('groups', 'users.group_id', '=', 'groups.id')
    ->groupBy('groups.name')
    ->get(),
                'userArmData' => User::select('arms.name', DB::raw('count(*) as user_count'))
                   ->join('arms', 'users.arm_id', '=', 'arms.id')
                ->groupBy('arms.name')
                ->get(),
            'classDepartmentData' => DB::table('classes')
                ->select('name', DB::raw('count(*) as class_count'))
                ->groupBy('name')
                ->get(),
            'groupCategoryData' => DB::table('categories')
                ->select('name', DB::raw('count(*) as group_count'))
                ->groupBy('name')
                ->get(),
                'schoolsData' => DB::table('schools')
                ->select('name', DB::raw('count(*) as school_count'))
                ->groupBy('name')
                ->get(),
        ];
    }
    elseif($adminId->role_id === 2)
    {
         $schools1 = getSchoolByAdmin($adminId->email);
        // If it returns JSON string, decode it
$schools1 = json_decode($schools1);

// Get first element
$schoola = $schools1[0];

// Now you can access
//echo $school->subdomain; // cdssgig.    ->where('email', 'like', "{$subdomain}%");
     $subdomain = $schoola->subdomain;
$stats = [
            'total_users' =>  User::where('role_id',3)->where('email', 'like', "{$subdomain}%")->count(),
            'active_users' => User::where('active',1)->where('role_id',3)->where('email', 'like', "{$subdomain}%")->count(), //StudentData::whereNotNull('email')->count(), // Example logic
            'classes' => DB::table('classes')->count(),
            'groups' => DB::table('groups')->count(),
            'categories' => DB::table('categories')->count(),
            'resources' => DB::table('resources')->count(),
            'arms' => DB::table('arms')->count(),
            'departments' => DB::table('departments')->count(),
            'schools' => DB::table('schools')->count(),
            'capschool' => 'School',
            'active_resources' => DB::table('resources')->where('active', true)->count(),
            'active_departments' => DB::table('departments')->where('active', true)->count(),
            'active_arms' => DB::table('arms')->where('active', true)->count(),
            'resourceTypeData' => DB::table('resource_types')
                ->select('name as name', DB::raw('count(*) as resources_count'))
                ->groupBy('name')
                ->get(),
            'userRoleData' => User::select('role', DB::raw('count(*) as user_count'))
                ->groupBy('role')
                ->get(),
                   'userArmData' => User::select('arm_id', DB::raw('count(*) as user_count'))
                ->groupBy('arm_id')
                ->get(),
                    'userClassData' => User::select('classes.name', DB::raw('count(*) as user_count'))
                     ->join('classes', 'users.class_id', '=', 'classes.id')
                ->groupBy('classes.name')
                ->get(),
              'userDepartmentData' => User::select('departments.name', DB::raw('count(*) as user_count'))
    ->join('departments', 'users.department_id', '=', 'departments.id')
    ->groupBy('departments.name')
    ->get(), 
     'userCategoryData' => User::select('categories.name', DB::raw('count(*) as user_count'))
    ->join('categories', 'users.category_id', '=', 'categories.id')
    ->groupBy('categories.name')
    ->get(),
    'userGroupsData' => User::select('groups.name', DB::raw('count(*) as user_count'))
    ->join('groups', 'users.group_id', '=', 'groups.id')
    ->groupBy('groups.name')
    ->get(),
                'userArmData' => User::select('arms.name', DB::raw('count(*) as user_count'))
                   ->join('arms', 'users.arm_id', '=', 'arms.id')
                ->groupBy('arms.name')
                ->get(),
               'classDepartmentData' => DB::table('classes')
                ->select('name', DB::raw('count(*) as class_count'))
                ->groupBy('name')
                ->get(),
                
            // 'userDepartmentData' => DB::table('classes')
            //     ->select('name', DB::raw('count(*) as class_count'))
            //     ->groupBy('name')
            //     ->get(),
            'groupCategoryData' => DB::table('categories')
                ->select('name', DB::raw('count(*) as group_count'))
                ->groupBy('name')
                ->get(),
                'schoolsData' => DB::table('schools')
                ->select('name', DB::raw('count(*) as school_count'))
                ->groupBy('name')
                ->get(),
        ];
       
    }
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
            'active_schools'=> Schools::where('active',1)->count(),
            // 'resourceTypeData' => ResourceType::withCount('resources')->get(),
        ];

        // For charts
       // $resourceTypeData = ResourceType::withCount('resources')->get();,'resourceTypeData'

        return view('admin.dashboard', compact('stats'));
    }
}
