<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Movement extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'transfer' => $this->transfer,
            'type_payment' => $this->type_payment,
            'date' => $this->date,
            'start_balance' => $this->start_balance,
            'end_balance' => $this->end_balance,
            'value' => $this->value,
            'transfer_email' => $this->transfer == 1 ?$this->transferWallet->email : '-',
            'category' => $this->category_id != null ? $this->category->name : '-',
        ];
    }
}
