<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'user_id', 'deal_id', 'company_id', 'contact_id', 'offer_number', 'title', 'offer_issued', 'offer_valid', 'subtotal', 'tax_rate', 'tax_amount', 'total', 'status',
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
}
