<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Provinsi extends BaseModel
{
    use HasFactory, SoftDeletes, AuditableTrait;
    protected $primaryKey = 'kode_provinsi';
    public $incrementing = false;
    protected $table = 'masterdata_provinsi';
    protected $casts = [
        'kode_provinsi' => 'string',
    ];
    // protected $guarded = ['id'];
}
