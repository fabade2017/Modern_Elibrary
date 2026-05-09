<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserData extends Model
{
    use HasFactory;

    protected $table = 'user_data';

    protected $fillable = [
        'surname',
        'firstname',
        'othername',
        'class',
        'group',
        'month_of_birth',
        'day_of_birth',
        'email',
    ];

    protected $casts = [
        'month_of_birth' => 'integer',
        'day_of_birth' => 'integer',
    ];
}