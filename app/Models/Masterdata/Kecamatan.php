<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Kecamatan extends BaseModel
{
    use HasFactory, SoftDeletes, AuditableTrait;
    protected $primaryKey = 'kode_kecamatan';
    public $incrementing = false;
    protected $table = 'masterdata_kecamatan';
    protected $casts = [
        'kode_kecamatan' => 'string', 
    ];
    // protected $guarded = ['id'];
}
