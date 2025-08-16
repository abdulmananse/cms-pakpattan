<?php

namespace App\Models;

use App\Traits\HasUuid;

class Role extends \Spatie\Permission\Models\Role
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'route',
        'guard_name',
        'is_active'
    ];
    
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
