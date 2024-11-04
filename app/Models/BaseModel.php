<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use App\Traits\LogActivityTrait;

class BaseModel extends Model implements Auditable
{
    use AuditableTrait, LogActivityTrait;

    protected $guarded = [];

    /**
     * Get who created the data.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get who updated the data.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get who deleted the data.
     */
    public function remover()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
