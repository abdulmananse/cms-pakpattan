<?php

namespace App\Models;

use App\Traits\HasCache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasCache;
    
    protected $fillable = [
        'key',
        'value'
    ];
}
