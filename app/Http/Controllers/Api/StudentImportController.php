<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserData;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
   use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentImportController extends Controller
{
    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'students' => 'required|array',
        //     'students.*.name' => 'required|string|max:255',
        //     'students.*.email' => 'required|email|unique:users,email',
        //     'students.*.password' => 'nullable|string|min:6',
        //     'students.*.class_id' => 'nullable|exists:classes,id',
        //     'students.*.active' => 'boolean',
        // ]);

         $validator = Validator::make($request->all(), [
            'students' => 'required|array',
            'students.*.Surname' => 'required|string|max:255',
            'students.*.Firstname' => 'required|string|max:255',
            'students.*.Othername' => 'required|string|max:255',
             'students.*.Class' => 'required|string|max:255',
             'students.*.Group' => 'required|string|max:255',
             'students.*.MonthOfBirth' => 'required|integer|max:255',
              'students.*.DayOfBirth' => 'required|integer|max:255',
            'students.*.email' => 'required|email|unique:users,email'
        ]);
/*
Surname
Firstname
Othername
Class
Group
MonthOfBirth
DayOfBirth
email


*/
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $studentsData = $request->input('students');
        $created = [];

        foreach ($studentsData as $student) {
            $user = UserData::create([
                'Surname' => $student['Surname'],
                'Firstname' => $student['Firstname'],
                'Othername' => $student['Othername'],
                'Class' => $student['Class'],
                'Group' => $student['Group'],
                  'Class' => $student['Class'],
                'Group' => $student['Group'],
                 'MonthOfBirth' => $student['MonthOfBirth'] ?? 0,
                  'DayOfBirth' => $student['DayOfBirth'] ?? 0,
                'email' => $student['email']
                // 'password' => Hash::make($student['password'] ?? 'password123'),
                // 'role_id' => config('roles.student_id', 2), // Or fetch dynamically
                // 'class_id' => $student['class_id'] ?? null,
                // 'active' => $student['active'] ?? true,
            ]);

            $created[] = $user;
        }

        return response()->json([
            'status' => 'success',
            'created' => $created
        ], 201);
    }
 

// public function upload(Request $request)
// {
//     $request->validate([
//         'file' => 'required|mimes:xlsx,csv,xls|max:2048',
//     ]);

//     try {
//         Excel::import(new StudentsImport, $request->file('file'));

//         return response()->json([
//             'status' => 'success',
//             'message' => 'Students imported successfully from file'
//         ], 200);

//     } catch (\Exception $e) {
//         return response()->json([
//             'status' => 'error',
//             'message' => $e->getMessage()
//         ], 500);
//     }
// }
public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv,xls|max:32768',
    ]);

    try {
        $import = new StudentsImport;
        Excel::import($import, $request->file('file'));

        return response()->json([
            'status' => 'success',
            'message' => 'Students import completed',
            'report' => [
                'created' => $import->created,
                'skipped_duplicates' => count($import->failures()),
                'validation_errors' => $import->failures()->map(function ($failure) {
                    return [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $failure->errors(),
                        'values' => $failure->values(),
                    ];
                }),
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

}
