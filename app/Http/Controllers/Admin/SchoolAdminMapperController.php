<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Classes;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use App\Models\SchoolAdminMapper;
use App\Models\School;


class SchoolAdminMapperController extends Controller
{
    public function index(Request $request)
    {
       // $mappings = SchoolAdminMapper::with('user', 'school')->paginate(10);
         $query = SchoolAdminMapper::with('schoolData');

    if ($search = $request->input('search')) {
        $query->where('school', 'like', "%{$search}%")
              ->orWhere('adminemail', 'like', "%{$search}%")
              ->orWhereHas('schoolData', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    }

    $mappings = $query->paginate(10)->withQueryString(); // preserves search query in pagination

        return view('admin.school_admin_mappers.index', compact('mappings'));
    }

    public function create()
    {
        $users = User::all();
        $schools = School::all();

        return view('admin.school_admin_mappers.create', compact('users', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'adminemail'   => 'required|email|exists:users,email',
            'school'       => 'required|exists:schools,id',
            'studentcount' => 'nullable|integer',
            'others'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:32768',
            'active'       => 'required|boolean',
        ]);

    // Handle image upload
    if ($request->hasFile('others')) {
        $fileName = time() . '.' . $request->others->extension();
        $path = $request->others->storeAs('uploads/school_admin_mappers', $fileName, 'public');
        $data['others'] = '/uploads/school_admin_mappers/'.$fileName;
    }
        SchoolAdminMapper::create($request->all());

        return redirect()->route('school_admin_mappers.index')->with('success', 'Mapping created successfully.');
    }
// app/Http/Controllers/Admin/SchoolAdminMapperController.php

// public function getUserDetails($email)
// {
//     $user = User::where('email', $email)->first();

//     if (!$user) {
//         return response()->json(['error' => 'User not found'], 404);
//     }

//     return response()->json($user);
// }

    public function show(SchoolAdminMapper $schoolAdminMapper)
    {
        return view('admin.school_admin_mappers.show', compact('schoolAdminMapper'));
    }
public function search(Request $request)
{
    $search = $request->input('query');

    $mappings = SchoolAdminMapper::with('schoolData')
        ->where('school', 'like', "%{$search}%")
        ->orWhere('adminemail', 'like', "%{$search}%")
        ->orWhereHas('schoolData', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%");
        })
        ->get();

    // Return HTML rows (Blade snippet)
    $html = view('school_admin_mappers.partials.table_rows', compact('mappings'))->render();

    return response()->json(['html' => $html]);
}

    public function edit(SchoolAdminMapper $schoolAdminMapper)
    {
        $users = User::all();
        $schools = School::all();

        return view('admin.school_admin_mappers.edit', compact('schoolAdminMapper', 'users', 'schools'));
    }

    // public function update(Request $request, SchoolAdminMapper $schoolAdminMapper)
    // {
    //     $request->validate([
    //         'adminemail'   => 'required|email|exists:users,email',
    //         'school'       => 'required|exists:schools,id',
    //         'studentcount' => 'nullable|integer',
    //         'others'       => 'nullable|string',
    //         'active'       => 'required|boolean',
    //     ]);

    //     $schoolAdminMapper->update($request->all());

    //     return redirect()->route('school_admin_mappers.index')->with('success', 'Mapping updated successfully.');
    // }
    public function update(Request $request, SchoolAdminMapper $schoolAdminMapper)
{
    $request->validate([
        'adminemail'   => 'required|email|exists:users,email',
        'school'       => 'required|exists:schools,id',
        'studentcount' => 'nullable|integer',
        'others'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:32768',
        'active'       => 'required|boolean',
    ]);

    $data = $request->all();

    // Handle image upload
    if ($request->hasFile('others')) {
        // optionally delete old image
        if ($schoolAdminMapper->others && \Storage::disk('public')->exists($schoolAdminMapper->others)) {
            \Storage::disk('public')->delete($schoolAdminMapper->others);
        }

        $fileName = time() . '.' . $request->others->extension();
        $path = $request->others->storeAs('uploads/school_admin_mappers', $fileName, 'public');
              $data['others'] = '/uploads/school_admin_mappers/'.$fileName;
//  $data['others'] = $path;
    }

    $schoolAdminMapper->update($data);

    return redirect()->route('school_admin_mappers.index')->with('success', 'Mapping updated successfully.');
}


public function getUserDetails(Request $request)
{
    $email = $request->query('email');
       // $email = $request->query('email'); // already decoded
    // If you want to be sure:
    //$email11='adeolu.emmanuel80@gmail.com';
    $email = urldecode($email);
    $user = User::where('email', $email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'. $email], 404);
    }

    return response()->json($user);
}

    public function destroy(SchoolAdminMapper $schoolAdminMapper)
    {
        $schoolAdminMapper->delete();

        return redirect()->route('school_admin_mappers.index')->with('success', 'Mapping deleted successfully.');
    }
             public function toggleActive(SchoolAdminMapper $schoolAdminMapper)
{
    $schoolAdminMapper->active = !$schoolAdminMapper->active;
    $schoolAdminMapper->save();
    return redirect()->back()->with('success', 'Mapping status updated.');
}

public function download(Request $request)
{
    $query = SchoolAdminMapper::with('schoolData');

    if ($search = $request->input('search')) {
        $query->where('school', 'like', "%{$search}%")
              ->orWhere('adminemail', 'like', "%{$search}%")
              ->orWhereHas('schoolData', function($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%");
              });
    }

    $mappings = $query->get();

    // Example: CSV download
    $filename = 'school_admin_mappings.csv';
    $handle = fopen($filename, 'w');
    fputcsv($handle, ['School', 'Admin Email', 'Student Count', 'Active']);

    foreach ($mappings as $m) {
        fputcsv($handle, [
            $m->schoolData?->name,
            $m->adminemail,
            $m->studentcount,
            $m->active ? 'Active' : 'Inactive'
        ]);
    }

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}

}
