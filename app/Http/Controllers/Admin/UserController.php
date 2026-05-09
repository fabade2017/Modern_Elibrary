<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Classes;
use App\Models\Category;
use App\Models\Arm;
use App\Models\Department;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class UserController extends Controller
{
    public function index(Request $request)
    { 

        $adminId = Auth::user();
        $roles = Role::where('active', true)->get();
        $classes = Classes::where('active', true)->get();
          $departments = Department::where('active', true)->get();
            $categories = Category::where('active', true)->get();
              $arms = Arm::where('active', true)->get();
        $groups = Group::where('active', true)->get();
        if($adminId->role_id === 1){
      $query = User::with(['role', 'classes', 'groups', 'department', 'category', 'arm']);
   // ->get();
          if ($search = $request->input('search')) {
        $query->where('email', 'like', "{$search}%")->orWhere('name', 'like', "%{$search}%")
         ->orWhereHas('classes', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
                 ->orWhereHas('arm', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })

             ->orWhere('role', 'like', "%{$search}%")
              ->orWhereHas('groups', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    } $subdomain ="*";
        }
        else{

     $schools1 = getSchoolByAdmin($adminId->email);
        // If it returns JSON string, decode it
$schools1 = json_decode($schools1);

// Get first element
$schoola = $schools1[0];

// Now you can access
//echo $school->subdomain; // cdssgig
     $subdomain = $schoola->subdomain;
      //  $security= ;
   //     $query = User::with(['role', 'classes', 'groups'])->where('email', 'like', "{ $subdomain}%");
        $query = User::with(['role', 'classes', 'groups', 'department', 'category'])
    ->where('email', 'like', "{$subdomain}%");
   // ->get();
          if ($search = $request->input('search')) {
        $query->where('email', 'like', "{$subdomain}%")->orWhere('name', 'like', "%{$search}%")
         ->orWhereHas('classes', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              })
             ->orWhere('role', 'like', "%{$search}%")
              ->orWhereHas('groups', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    }
        }
   
        $users = $query->paginate(10);
        return view('admin.users.index', compact('users','subdomain','roles','classes','departments','categories','arms','groups'));
    }

    public function create()
    {
        $roles = Role::where('active', true)->get();
        $classes = Classes::where('active', true)->get();
          $departments = Department::where('active', true)->get();
            $categories = Category::where('active', true)->get();
              $arms = Arm::where('active', true)->get();
        $groups = Group::where('active', true)->get();

        return view('admin.users.create', compact('roles', 'classes', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|string|min:6',
            'role_id'   => 'nullable|exists:roles,id',
            'class_id'  => 'nullable|exists:classes,id',
            'groups'    => 'array',
            'groups.*'  => 'exists:groups,id',
            'active'    => 'boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['active'] = $request->has('active');
        $data['role'] = Role::where('id',        $data['role_id'])->value('name');
       // $data['active'] = $request->has('active');
        $user = User::create($data);
        $user->groups()->sync($request->groups ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // $roles = Role::where('active', true)->get();
        // $classes = Classes::where('active', true)->get();
        // $groups = Group::where('active', true)->get();
 $roles = Role::where('active', true)->get();
        $classes = Classes::where('active', true)->get();
          $departments = Department::where('active', true)->get();
            $categories = Category::where('active', true)->get();
              $arms = Arm::where('active', true)->get();
        $groups = Group::where('active', true)->get();

        return view('admin.users.edit', compact('user', 'roles', 'classes', 'groups', 'departments', 'arms'));
    }
  public function show(User $user)
    {
    $roles = Role::where('active', true)->get();
        $classes = Classes::where('active', true)->get();
          $departments = Department::where('active', true)->get();
            $categories = Category::where('active', true)->get();
              $arms = Arm::where('active', true)->get();
        $groups = Group::where('active', true)->get();


        return view('admin.users.edit', compact('user', 'roles', 'classes', 'groups', 'departments', 'arms'));
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:6',
            'role_id'   => 'nullable|exists:roles,id',
            'class_id'  => 'nullable|exists:classes,id',
            'groups'    => 'array',
            'groups.*'  => 'exists:groups,id',
            'active'    => 'boolean',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
   $data['role'] = Role::where('id',        $data['role_id'])->value('name');
        $data['active'] = $request->has('active');

        $user->update($data);
        $user->groups()->sync($request->groups ?? []);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

       public function toggleActive(User $user)
{
    $user->active = !$user->active;
    $user->save();
    return redirect()->back()->with('success', 'Group status updated.');
}

// UserController.php
public function getDetails($id)
{
    // Split by "."
$user = User::where('id', $id)->first();

// Access email
$useremail = $user->email;
$parts = explode('.', $useremail);

// Get first part
$subdomain = $parts[0]; // "cferf"
    // fetch related data from another table
    $extraInfo = DB::table($subdomain)->where('email', $useremail)->first();

    return response()->json($extraInfo);
}
}


