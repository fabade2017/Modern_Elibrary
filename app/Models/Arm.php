<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arm extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'active'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
