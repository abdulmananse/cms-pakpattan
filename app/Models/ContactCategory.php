<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactCategory extends Model
{
    use HasUuid;

    protected $table = 'contact_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'integer',
    ];

    /**
     * Scope a query to only include active contact categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Get all officer contacts for the ContactCategory.
     */
    public function officerContacts(): HasMany
    {
        return $this->hasMany(OfficerContact::class, 'contact_category_id');
    }
}
