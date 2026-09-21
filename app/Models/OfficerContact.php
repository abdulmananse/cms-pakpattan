<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class OfficerContact extends Model
{
    use HasUuid;

    protected $table = 'officer_contacts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'officer_name',
        'designation',
        'department_id',
        'office_establishment',
        'primary_mobile',
        'alternate_phone',
        'email',
        'contact_category_id',
        'lifecycle_status',
        'is_pcm',
        'is_favorite',
        'photo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_pcm' => 'boolean',
        'is_favorite' => 'boolean',
    ];

    /**
     * Get the department that owns the officer contact record.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the contact category that owns the officer contact record.
     */
    public function contactCategory(): BelongsTo
    {
        return $this->belongsTo(ContactCategory::class, 'contact_category_id');
    }

    /**
     * Scope a query to only include active records.
     */
    public function scopeActive($query)
    {
        return $query->where('lifecycle_status', 'Active');
    }

    /**
     * Scope a query by PCM status.
     */
    public function scopePcm($query, $isPcm = true)
    {
        return $query->where('is_pcm', $isPcm);
    }

    /**
     * Scope a query by Favorite status.
     */
    public function scopeFavorite($query, $isFavorite = true)
    {
        return $query->where('is_favorite', $isFavorite);
    }

    /**
     * Get photo URL or fallback avatar.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && Storage::disk('public')->exists('officers/' . $this->photo)) {
            return asset('storage/officers/' . $this->photo);
        }

        return asset('images/avatar-1.png');
    }
}
