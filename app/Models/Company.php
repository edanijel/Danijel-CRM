<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\CompanyStatus;

class Company extends Model
{
    protected $fillable = [
        'name', 'oib', 'email', 'phone', 'website', 'address', 'city', 'postal_code', 'status',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    protected function casts(): array
    {
        return [
            'status' => CompanyStatus::class,
        ];
    }
}
