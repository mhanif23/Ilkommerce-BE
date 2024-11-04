<?php

namespace App\Models\Masterdata;

use App\Models\BaseModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Kelurahan extends BaseModel
{
    use HasFactory, SoftDeletes, AuditableTrait;
    protected $primaryKey = 'kode_kelurahan';
    public $incrementing = false;
    protected $table = 'masterdata_kelurahan';
    // protected $guarded = ['id'];
}
