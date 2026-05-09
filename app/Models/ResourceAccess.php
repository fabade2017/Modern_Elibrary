<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAccess extends Model
{
    use HasFactory;
    protected $table = 'resource_access';
    protected $fillable = ['resource_id', 'class_id', 'group_id','arm_id', 'user_id','category_id','role_id'];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

      public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

       public function arms()
    {
        return $this->belongsTo(Arm::class, 'arm_id');
    }
public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
