<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait UuidTrait
{
    /**
     * Data creating event
     */
    protected static function bootUuidTrait()
    {
        static::creating(function (Model $model) {
            if (empty($model->{$model->getKeyName()}) && $model->keyIsUuid !== false) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }
}
