<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Kota extends BaseModel
{
    use HasFactory, SoftDeletes, AuditableTrait;
    protected $primaryKey = 'kode_kota';
    public $incrementing = false;
    protected $table = 'masterdata_kota';
    protected $casts = [
        'kode_kota' => 'string', 
    ];
    // protected $guarded = ['id'];
}
