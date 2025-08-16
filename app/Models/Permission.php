<?php

namespace App\Models;

use App\Traits\HasUuid;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'permission_group_id',
        'name',
        'guard_name',
    ];

}
