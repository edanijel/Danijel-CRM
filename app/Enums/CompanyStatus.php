<?php

namespace App\Enums;

enum CompanyStatus: string
{
    case Lead = 'lead';
    case Qualified = 'qualified';
    case ActiveClient = 'active_client';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Lead => 'Lead',
            self::Qualified => 'Qualified',
            self::ActiveClient => 'Active Client',
            self::Inactive => 'Inactive',
        };
    }
}
