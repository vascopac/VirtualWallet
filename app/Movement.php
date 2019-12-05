<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'id',
        'type',
        'transfer_wallet_id',
        'type_payment',
        'date',
        'start_balance',
        'end_balance',
        'value'
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id');
    }

    /*
    public function category()
    {
        return $this->hasOne(Category::class, 'id');
    }
    */
}
