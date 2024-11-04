<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Category extends BaseModel
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $guarded = ['id'];
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

