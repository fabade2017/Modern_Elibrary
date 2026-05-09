<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    
    protected $fillable = ['name', 'email', 'password', 'role_id', 'class_id','category_id', 'active','role'];

    protected $hidden = ['password', 'remember_token'];

    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
        
    // }
public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
}
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
       public function arm()
    {
        return $this->belongsTo(Arm::class, 'arm_id');
    }
       public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
       public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
public function hasPermission($permission)
{
    return $this->role && $this->role->permissions->contains('name', $permission);
}

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

   public function groups()
{
    return $this->belongsToMany(Group::class, 'group_user');
}

// public function IsSuperadmin()
//     {
//         return $this->role_id === 1 || ($this->role && $this->role->name === 'superadmin');
//     }
public function isSuperadmin()
{
    return $this->role_id === 1 || $this->role === 'superadmin';
}
    public function isAdmin()
    {
       return $this->role_id === 2 || $this->role === 'admin';
    }
    public function isStudent()
    {
        return $this->role_id === 3 || $this->role === 'student';
    }

    public function hasResourceAccess($resourceId = null)
{
    $query = $this->resourceAccess();

    if ($resourceId) {
        $query->where(function ($q) use ($resourceId) {
            $q->where('resource_id', $resourceId)
              ->orWhereNull('resource_id'); // Access to all
        });
    } else {
        $query->whereNull('resource_id'); // Check for "access to all"
    }

    return $query->where(function ($q) {
        $q->where('role_id', $this->role_id)
          ->orWhere('class_id', $this->class_id)
          ->orWhereNotNull('group_id')
          ->orWhereNull('resource_id'); // Access to all
    })->exists();
}
    
}
