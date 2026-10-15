<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RevenueComplaintAttachment extends Model
{
    protected $fillable = [
        'revenue_complaint_id',
        'revenue_complaint_log_id',
        'user_id',
        'file_path',
        'original_name',
        'file_size',
        'mime_type',
        'attachment_type',
    ];

    public function complaint()
    {
        return $this->belongsTo(RevenueComplaint::class, 'revenue_complaint_id');
    }

    public function log()
    {
        return $this->belongsTo(RevenueComplaintLog::class, 'revenue_complaint_log_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function isImage(): bool
    {
        if ($this->mime_type && str_starts_with($this->mime_type, 'image/')) {
            return true;
        }

        $extension = strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    public function formattedSize(): string
    {
        if (!$this->file_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $this->file_size > 0 ? floor(log($this->file_size, 1024)) : 0;
        return number_format($this->file_size / pow(1024, $power), 2) . ' ' . ($units[$power] ?? 'B');
    }
}
