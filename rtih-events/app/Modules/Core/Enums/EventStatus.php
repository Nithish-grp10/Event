<?php

namespace App\Modules\Core\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case UnderReview = 'review';
    case Published = 'published';
    case Live = 'live';
    case Completed = 'completed';
    case Archived = 'archived';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft',
            self::UnderReview => 'Under Review',
            self::Published => 'Published',
            self::Live => 'Live',
            self::Completed => 'Completed',
            self::Archived => 'Archived',
        };
    }
}
