<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'nif' => $this->nif,
            'photo' => $this->photo,
            'type' => $this->type,
            'wallet' => $this->type != 'u' ? '' : $this->wallet->balance,
            'active' => $this->active == '1' ? 'Active' : 'Inactive',
        ];
    }
}
