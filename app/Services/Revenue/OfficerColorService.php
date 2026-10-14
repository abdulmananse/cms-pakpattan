<?php

namespace App\Services\Revenue;

use App\Models\User;

class OfficerColorService
{
    /**
     * Curated list of high-contrast, soft pastel themes.
     * Each theme has:
     * - 'bg': soft pastel background for table row cells (very light, high contrast for text)
     * - 'border': darker accent color for the left border & indicator
     * - 'text': dark legible text color
     * - 'chip_bg': slightly deeper pastel for chips/badges
     * - 'chip_text': contrasting dark tone for chip text
     * - 'chip_border': border for the chip
     */
    public const PALETTE = [
        [
            'id' => 0,
            'name' => 'Soft Sky Blue',
            'bg' => '#f0f7ff',
            'border' => '#2563eb',
            'text' => '#1e3a8a',
            'chip_bg' => '#dbeafe',
            'chip_text' => '#1e40af',
            'chip_border' => '#93c5fd',
        ],
        [
            'id' => 1,
            'name' => 'Soft Emerald',
            'bg' => '#f0fdf4',
            'border' => '#059669',
            'text' => '#064e3b',
            'chip_bg' => '#d1fae5',
            'chip_text' => '#065f46',
            'chip_border' => '#6ee7b7',
        ],
        [
            'id' => 2,
            'name' => 'Soft Amber / Peach',
            'bg' => '#fffbeb',
            'border' => '#d97706',
            'text' => '#78350f',
            'chip_bg' => '#fef3c7',
            'chip_text' => '#92400e',
            'chip_border' => '#fcd34d',
        ],
        [
            'id' => 3,
            'name' => 'Soft Lavender / Violet',
            'bg' => '#f5f3ff',
            'border' => '#7c3aed',
            'text' => '#4c1d95',
            'chip_bg' => '#ede9fe',
            'chip_text' => '#5b21b6',
            'chip_border' => '#c4b5fd',
        ],
        [
            'id' => 4,
            'name' => 'Soft Rose',
            'bg' => '#fff1f2',
            'border' => '#e11d48',
            'text' => '#881337',
            'chip_bg' => '#ffe4e6',
            'chip_text' => '#9f1239',
            'chip_border' => '#fda4af',
        ],
        [
            'id' => 5,
            'name' => 'Soft Teal',
            'bg' => '#f0fdfa',
            'border' => '#0d9488',
            'text' => '#134e4a',
            'chip_bg' => '#ccfbf1',
            'chip_text' => '#115e59',
            'chip_border' => '#5eead4',
        ],
        [
            'id' => 6,
            'name' => 'Soft Indigo',
            'bg' => '#eef2ff',
            'border' => '#4f46e5',
            'text' => '#312e81',
            'chip_bg' => '#e0e7ff',
            'chip_text' => '#3730a3',
            'chip_border' => '#a5b4fc',
        ],
        [
            'id' => 7,
            'name' => 'Soft Coral / Warm Orange',
            'bg' => '#fff7ed',
            'border' => '#ea580c',
            'text' => '#7c2d12',
            'chip_bg' => '#ffedd5',
            'chip_text' => '#9a3412',
            'chip_border' => '#fdba74',
        ],
        [
            'id' => 8,
            'name' => 'Soft Cyan',
            'bg' => '#ecfeff',
            'border' => '#0891b2',
            'text' => '#164e63',
            'chip_bg' => '#cffafe',
            'chip_text' => '#155e75',
            'chip_border' => '#67e8f9',
        ],
        [
            'id' => 9,
            'name' => 'Soft Pink',
            'bg' => '#fdf2f8',
            'border' => '#db2777',
            'text' => '#831843',
            'chip_bg' => '#fce7f3',
            'chip_text' => '#9d174d',
            'chip_border' => '#f9a8d4',
        ],
        [
            'id' => 10,
            'name' => 'Soft Lime',
            'bg' => '#f7fee7',
            'border' => '#65a30d',
            'text' => '#365314',
            'chip_bg' => '#ecfccb',
            'chip_text' => '#3f6212',
            'chip_border' => '#bef264',
        ],
        [
            'id' => 11,
            'name' => 'Soft Fuchsia',
            'bg' => '#fdf4ff',
            'border' => '#c026d3',
            'text' => '#701a75',
            'chip_bg' => '#fae8ff',
            'chip_text' => '#86198f',
            'chip_border' => '#f0abfc',
        ],
        [
            'id' => 12,
            'name' => 'Soft Warm Sand',
            'bg' => '#fafaf9',
            'border' => '#78716c',
            'text' => '#292524',
            'chip_bg' => '#f5f5f4',
            'chip_text' => '#44403c',
            'chip_border' => '#d6d3d1',
        ],
        [
            'id' => 13,
            'name' => 'Soft Turquoise',
            'bg' => '#e6fffa',
            'border' => '#0284c7',
            'text' => '#075985',
            'chip_bg' => '#e0f2fe',
            'chip_text' => '#0369a1',
            'chip_border' => '#7dd3fc',
        ],
        [
            'id' => 14,
            'name' => 'Soft Mint Green',
            'bg' => '#f0fdf9',
            'border' => '#047857',
            'text' => '#064e3b',
            'chip_bg' => '#d1fae5',
            'chip_text' => '#065f46',
            'chip_border' => '#6ee7b7',
        ],
        [
            'id' => 15,
            'name' => 'Soft Slate / Blue-Gray',
            'bg' => '#f8fafc',
            'border' => '#475569',
            'text' => '#0f172a',
            'chip_bg' => '#e2e8f0',
            'chip_text' => '#334155',
            'chip_border' => '#94a3b8',
        ],
    ];

    public const UNASSIGNED = [
        'id' => -1,
        'name' => 'Unassigned',
        'bg' => '#f9fafb',
        'border' => '#9ca3af',
        'text' => '#4b5563',
        'chip_bg' => '#f3f4f6',
        'chip_text' => '#4b5563',
        'chip_border' => '#d1d5db',
    ];

    /**
     * Get deterministic theme for an officer based strictly on their unique User ID.
     */
    public static function getThemeForUser(?int $userId): array
    {
        if (empty($userId)) {
            return self::UNASSIGNED;
        }

        $count = count(self::PALETTE);
        // Multiplying by 5 (coprime to 16) distributes sequential IDs across distinct hue families
        // e.g., ID 1 -> 0 (Blue), ID 2 -> 5 (Teal), ID 3 -> 10 (Lime), ID 4 -> 15 (Slate), ID 5 -> 4 (Rose)...
        $normalizedId = max(1, abs($userId));
        $index = (($normalizedId - 1) * 5) % $count;

        return self::PALETTE[$index];
    }

    /**
     * Generate 2-letter uppercase initials for an officer.
     */
    public static function getInitials(?string $name): string
    {
        if (empty($name)) {
            return '—';
        }

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', $name);
        $words = array_values(array_filter(preg_split('/\s+/', trim($clean))));

        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }

        if (!empty($words[0])) {
            return strtoupper(mb_substr($words[0], 0, 2));
        }

        return strtoupper(mb_substr($name, 0, 2));
    }
}
