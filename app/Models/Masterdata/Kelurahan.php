<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'kode_kelurahan';
    public $incrementing = false;
    protected $table = 'masterdata_kelurahan';
    protected $casts = [
        'kode_kelurahan' => 'string', 
    ];
    // protected $guarded = ['id'];
}
