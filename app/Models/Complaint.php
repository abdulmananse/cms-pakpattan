<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'name',
        'cnic',
        'mobile',
        'category_id',
        'description',
        'location',
        'attachment',
        'source',
        'complaint_status',
        'created_by',
        'rejected_by',
        'resolved_by',
        'resolved_attachment',
        'remarks',
    ];

    public function scopeRoleFilter($query, $user)
    {
        if ($user->role == 'Department') {
            $query->where('department_id', $user->department_id);
        }

        return $query;
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('s')) {
            $query->where('complaint_status', $request->s);
        }

        return $query;
    }

    public function getMobileAttribute($value)
    {
        return ($value == null) ? null : (mb_substr($value, 0, 1) == 3 ? '0' . $value : $value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function complaint_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function assigned_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
   
    public function resolved_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
