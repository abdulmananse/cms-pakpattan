<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

trait HasCache
{
    /**
     * Set Data in Cache
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * 
     */
    private function setCache(Model $model) {
        $data = $model->get();
        Cache::forget($model->getTable() . '_cache');
        Cache::forget('count_' . $model->getTable() . '_cache');
        Cache::forever($model->getTable() . '_cache', $data->toArray());
        Cache::forever('count_' . $model->getTable() . '_cache', $data->count());
    }

    /**
     * Scope a query to get data from cache
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCache($query)
    {
        return Cache::rememberForever($query->getModel()->getTable() . '_cache', function () use ($query) {
            return $query->get()->toArray();
        });
    }
    
    /**
     * Scope a query to get data from cache
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCountCache($query)
    {
        return Cache::rememberForever('count_' . $query->getModel()->getTable() . '_cache', function () use ($query) {
            return $query->count();
        });
    }

    /**
     * Boot function from Laravel.
     */
    protected static function bootHasCache()
    {
        static::created(function ($model) {
            $model->setCache($model);
        });

        static::updated(function ($model) {
            $model->setCache($model);
        });
        
        static::deleted(function ($model) {
            $model->setCache($model);
        });
    }
}