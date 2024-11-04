<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Toko extends BaseModel
{
    use HasFactory;

    protected $table = 'toko'; 
    
    protected $guarded = ['id'];

    protected $appends = ['encrypt_id'];
    public function getEncryptIdAttribute()
    {
        return $this->attributes['encrypt_id'] = encryptData($this->id);
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }

}
