<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provinsi extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'kode_provinsi';
    public $incrementing = false;
    protected $table = 'masterdata_provinsi';
    protected $casts = [
        'kode_provinsi' => 'string',
    ];
    // protected $guarded = ['id'];
}
