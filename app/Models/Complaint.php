<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;


class Complaint extends Model
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'complaint_no',
        'category_id',
        'description',
        'location',
        'attachment',
        'source',
        'complaint_status',
        'created_by',
    ];
}
