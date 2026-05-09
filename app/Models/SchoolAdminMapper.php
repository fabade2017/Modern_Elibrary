<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
/*
class SchoolAdminMapper extends Model
{
    //
     protected $fillable = [
        'adminemail',
        'school',
        'studentcount',
        'others',
        'active',
    ];
}
*/

class SchoolAdminMapper extends Model
{
    use HasFactory;

    protected $fillable = [
        'adminemail',
        'school',
        'studentcount',
        'others',
        'active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'adminemail', 'email');
    }
 // Relationship with School
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function schoolData()
    {
        return $this->belongsTo(School::class, 'school', 'id');
    }
}
