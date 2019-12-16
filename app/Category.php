<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class, 'id', 'category_id');
    }
}
