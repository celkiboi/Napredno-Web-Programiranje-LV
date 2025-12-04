<?php

namespace App\Enums;

enum StudyType: string
{
    case UNDERGRAD = 'undergraduate';
    case GRAD = 'graduate';
    case PROFESSIONAL = 'vocational'; // Translation for "Stručni studij"

    public function label(): string
    {
        return match($this) {
            self::UNDERGRAD => 'Undergraduate (Prijediplomski)',
            self::GRAD => 'Graduate (Diplomski)',
            self::PROFESSIONAL => 'Professional (Stručni)',
        };
    }
}