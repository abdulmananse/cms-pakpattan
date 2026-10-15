<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RevenueComplaintSequence extends Model
{
    protected $fillable = [
        'year',
        'current_number',
    ];

    /**
     * Generate the next sequential complaint number under row-level lock.
     */
    public static function getNextComplaintNumber(?int $year = null): string
    {
        $year = $year ?? (int) date('Y');

        return DB::transaction(function () use ($year) {
            $sequence = self::where('year', $year)
                ->lockForUpdate()
                ->first();

            if (!$sequence) {
                $sequence = self::create([
                    'year' => $year,
                    'current_number' => 0,
                ]);

                // Re-lock to guarantee isolation
                $sequence = self::where('id', $sequence->id)
                    ->lockForUpdate()
                    ->first();
            }

            $sequence->increment('current_number');
            $sequence->refresh();

            return sprintf('REV-%04d-%05d', $year, $sequence->current_number);
        });
    }
}
