<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'postcode',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
