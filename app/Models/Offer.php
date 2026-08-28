<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Currency;
use App\Enums\OfferStatus;

class Offer extends Model
{
    protected $fillable = [
        'user_id', 'deal_id', 'company_id', 'contact_id', 'offer_number', 'title', 'offer_issued', 'offer_valid', 'currency', 'subtotal', 'tax_rate', 'tax_amount', 'total', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function offerItems()
    {
        return $this->hasMany(OfferItem::class);
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->offerItems()->sum('total');
        $taxAmount = $subtotal * ($this->tax_rate / 100);

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total' => $subtotal + $taxAmount,
        ]);
    }

    protected function casts(): array
    {
        return [
            'status' => OfferStatus::class,
            'currency' => Currency::class,
            'offer_issued' => 'date',
            'offer_valid' => 'date',
        ];
    }
}
