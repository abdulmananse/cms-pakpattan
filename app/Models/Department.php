<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasUuid;

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

    /**
     * Get all of the complaints for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'department_id');
    }
    
    /**
     * Get all of the pending complaints for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pending_complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'department_id')->where('complaint_status', 0);
    }
    
    /**
     * Get all of the pending complaints for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resolved_complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'department_id')->where('complaint_status', 1);
    }

}
