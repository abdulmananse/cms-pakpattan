<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

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
        'department_id',
        'transfer_from',
        'transfer_from_by',
        'transfer_from_at',
        'source_id',
        'complaint_status',
        'complaint_at',
        'created_by',
        'assigned_by',
        'assigned_at',
        'rejected_by',
        'resolved_by',
        'resolved_at',
        'resolved_attachment',
        'remarks',
        'reopened_remarks',
        'reopened_by',
        'reopened_at',
    ];

    public function scopeRoleFilter($query, $user)
    {
        if ($user->role == 'Department') {
            $query->where('department_id', $user->departments->pluck('id')->toArray());
        }
        if ($user->role == 'Complaint_Sources') {
            $query->where('source_id', $user->source_id);
        }

        return $query;
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('status')) {
            if ($request->status == 0) { // Fresh
                $query->where('complaint_status', $request->status)
                        ->where('assigned_at', '>', Carbon::now()->subDays(5));
            } elseif ($request->status == 4) { // Overdue
                $query->where('complaint_status', 0)
                    ->where('department_id', '>', 0)
                    // ->where('assigned_at', '<', Carbon::now()->subDays(5));
                    ->whereRaw('assigned_at < DATE_SUB(NOW(), INTERVAL 5 DAY)');
            } else {
                $query->where('complaint_status', $request->status);
            }
        }
        if($request->filled('c') && $request->c > 0) {
            $query->where('category_id', $request->c);
        }
        if($request->filled('d') && $request->d > 0) {
            $query->where('department_id', $request->d);
        }
        if($request->filled('s') && $request->s > 0) {
            $query->where('source_id', $request->s);
        }

        $query->when(($request->filled('date') && $request->date != 'all'), function ($query) use ($request) {
            $date = explode(',', $request->date);
            $startDate = date('Y-m-d', strtotime($date[0]));
            $query->where('created_at', '>=', $startDate . ' 00:00:00');

            if(@$date[1]) {
                $endDate = date('Y-m-d', strtotime($date[1]));
                $query->where('created_at', '<=', $endDate . ' 23:59:59');
            }

            return $query;
        });

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
    
    public function transfer_from_department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'transfer_from');
    }
    
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
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
    
    public function reopened_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reopened_by');
    }
}
