<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file_path',
        'resource_type_id',
        'category_id',
        'downloadable',
        'view_online',
        'active'
    ];

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function type()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
    }

    public function access()
    {
        return $this->hasMany(ResourceAccess::class);
    }
    public function getThumbnailUrlAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));

        if (in_array($extension, $imageExtensions) && file_exists(storage_path('app/public/' . $this->file_path))) {
            return asset('storage/' . $this->file_path);
        }

        return asset('img/gbp.jpg'); // Fallback image
    }
       public function resourceType()
    {
        return $this->belongsTo(ResourceType::class, 'resource_type_id');
        // assuming your resources table has a `resource_type_id` column
    }
}
