<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasUuid
{
    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUuid($query, $uuid)
    {
        return $query->where('uuid', $uuid);
    }

    /**
    * Get the route key for the model.
    *
    * @return string
    */
    public function getRouteKeyName()
    {
       return 'uuid';
    }

    /**
     * Boot function from Laravel.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            // $model->uuid = getUuid();
            $model->uuid = DB::raw('uuid()');
        });
    }
}