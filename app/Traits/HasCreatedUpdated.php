<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasCreatedUpdated
{

    /**
     * Boot function from Laravel.
     */
    protected static function bootHasCreatedUpdated()
    {
        static::creating(function ($model) {
            if(Auth::check()) {
                $model->CreatedBy =  Auth::id();
                $model->ModifiedBy =  Auth::id();
            }
        });

        static::updating(function ($model) {
            if(Auth::check()) {
                $model->ModifiedBy =  Auth::id();
            }
        });
    }
}