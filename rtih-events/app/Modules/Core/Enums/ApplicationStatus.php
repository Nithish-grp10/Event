<?php

namespace App\Modules\Core\Enums;

enum ApplicationStatus: string
{
    case Submitted = 'submitted';
    case UnderReview = 'under_review';
    case Shortlisted = 'shortlisted';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Submitted => 'Submitted',
            self::UnderReview => 'Under Review',
            self::Shortlisted => 'Shortlisted',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Completed => 'Completed',
        };
    }
}
