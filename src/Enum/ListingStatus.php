<?php

namespace App\Enum;

enum ListingStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SOLD = 'sold';
    case SUSPENDED = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
            self::SOLD => 'Sold',
            self::SUSPENDED => 'Suspended',
        };
    }
}
