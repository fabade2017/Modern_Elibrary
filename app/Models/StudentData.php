<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class StudentData extends Model
// {
//     //
// }

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class StudentData extends Model
// {
//     protected $fillable = ['id', 'name', 'email', 'role', 'institution_id', 'department_id', 'bio', 'username', 'phone'];
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    protected $table = 'studentData';
    protected $primaryKey = 'id';
    public $incrementing = false; // Since ID is a UUID string
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'email', 'role', 'institution_id', 'department_id', 'bio', 'username', 'phone'];
}