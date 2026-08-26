<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    
    protected $fillable = [
        'name', 'oib', 'email', 'phone', 'website', 'address', 'city', 'postal_code', 'status',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

}
