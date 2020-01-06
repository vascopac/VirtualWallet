<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'wallet_id',
        'type',
        'transfer',
        'transfer_movement_id',
        'transfer_wallet_id',
        'type_payment',
        'category_id',
        'iban',
        'mb_entity_code',
        'mb_payment_reference',
        'description',
        'start_balance',
        'end_balance',
        'value'
    ];

    public $timestamps = false;
    
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'id');
    }

    public function transferWallet()
    {
        return $this->hasOne(Wallet::class, 'id', 'transfer_wallet_id');
    }

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    
}
