<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\DealStatus;
use App\Enums\Currency;

class Deal extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'contact_id', 'title', 'description', 'value', 'currency', 'probability', 'expected_close_date', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    protected function casts(): array
    {
        return [
            'status' => DealStatus::class,
            'currency' => Currency::class
        ];
    }
}
