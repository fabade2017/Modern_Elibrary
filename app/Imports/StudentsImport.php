<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    public $created = 0;

    public function model(array $row)
    {
        // Avoid duplicates
        if (User::where('email', $row['email'])->exists()) {
            return null;
        }

        $this->created++;

        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => Hash::make($row['password'] ?? 'password123'),
            'role_id'  => config('roles.student_id', 2), // Student role
            'class_id' => $row['class_id'] ?? null,
            'active'   => $row['active'] ?? true,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name'     => 'required|string|max:255',
            '*.email'    => 'required|email',
            '*.password' => 'nullable|string|min:6',
            '*.class_id' => 'nullable|integer',
            '*.active'   => 'nullable|boolean',
        ];
    }
}
