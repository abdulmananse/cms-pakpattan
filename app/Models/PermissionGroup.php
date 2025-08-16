<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;


class PermissionGroup extends Model
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'ordering'];

    /**
     * Group hasMany Permissions
     */
    public function permissions() {
        return $this->hasMany(Permission::class);
    }

}
