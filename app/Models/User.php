<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasUuid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, Notifiable, HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'mobile',
        'address',
        'role',
        'department_id',
        'source_id',
        'designation',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getMobileAttribute($value)
    {
        return ($value == null) ? null : (mb_substr($value, 0, 1) == 3 ? '0' . $value : $value);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_user', 'user_id', 'department_id');
    }

    public function getOfficialLabelAttribute(): string
    {
        $designation = $this->designation ? " ({$this->designation})" : '';
        return "{$this->name}{$designation}";
    }

    public function revenueComplaintsHolding()
    {
        return $this->hasMany(RevenueComplaint::class, 'current_holder_id');
    }

    public function revenueComplaintsCreated()
    {
        return $this->hasMany(RevenueComplaint::class, 'creator_id');
    }

    /**
     * Determine if user has legitimate access to the general CMS dashboard.
     */
    public function hasGeneralDashboardAccess(): bool
    {
        if ($this->hasRole('Super Admin')) {
            return true;
        }

        if ($this->hasRole('Complainant') || $this->role === 'Complainant') {
            return true;
        }

        return $this->can('Dashboard');
    }

    /**
     * Determine if user has access to Land Revenue Matters module.
     */
    public function hasRevenueMattersAccess(): bool
    {
        return $this->can('Land Revenue Matters Dashboard')
            || $this->can('Land Revenue Matters Access')
            || $this->can('Revenue Matters Dashboard')
            || $this->can('Revenue Matters Access');
    }

    /**
     * Determine if user is strictly a Land Revenue user (no general CMS dashboard access).
     */
    public function isRevenueOnly(): bool
    {
        return $this->hasRevenueMattersAccess() && !$this->hasGeneralDashboardAccess();
    }

    /**
     * Determine default dashboard route for this user.
     */
    public function defaultDashboardRoute(): string
    {
        if ($this->isRevenueOnly()) {
            return route('revenue-matters.dashboard', absolute: false);
        }

        return route('dashboard', absolute: false);
    }
}
