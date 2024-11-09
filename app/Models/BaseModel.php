<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogActivityTrait;

class BaseModel extends Model
{
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
