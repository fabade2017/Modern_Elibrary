<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;
   protected $table = 'item_category';
    protected $fillable = ['name', 'description', 'active'];

    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
