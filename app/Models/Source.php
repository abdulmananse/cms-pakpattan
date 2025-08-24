<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;


class Source extends Model
{
    use HasUuid;

    protected $table = 'sources';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'ordering', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
