<?php

namespace App\Traits;

trait LogActivityTrait
{
    /**
     * Handle model event
     */
    public static function bootLogActivityTrait()
    {
        /**
         * Data creating event
         */
        static::creating(function ($model) {
            $model->created_at = now();
            $model->created_by = auth()->user()->id ?? null;
            if (!empty($model->updated_at_creating) && $model->updated_at_creating === true)
                $model->updated_at = now();
        });

        /**
         * Data updating event
         */
        static::updating(function ($model) {
            $model->updated_at = now();
            $model->updated_by = auth()->user()->id ?? null;
        });

        /**
         * Data updating event
         */
        static::deleting(function ($model) {
            // dd($model);
            $model->deleted_at = now();
            $model->deleted_by = auth()->user()->id ?? null;
        });
    }
}
