<?php

namespace App\Enum;

enum ListingStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case SOLD = 'sold';
    case SUSPENDED = 'suspended';
}
