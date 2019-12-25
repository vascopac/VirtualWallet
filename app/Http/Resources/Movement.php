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
            'type' => $this->type == 'e' ? 'Expense' : 'Income',
            'transfer' => $this->transfer,
            'type_payment' => $this->type_payment != null ? ($this->type_payment == 'c' ? 'Cash' : ($this->type_payment == 'bt' ? 'Bank Transfer' : 'MB')) : '-',
            'date' => $this->date,
            'start_balance' => $this->start_balance,
            'end_balance' => $this->end_balance,
            'value' => $this->value,
            'transfer_email' => $this->transfer == 1 ? $this->transferWallet->email : '-',
            'category_id' => $this->category_id != null ? $this->category_id : '-',
            'category' => $this->category_id != null ? $this->category->name : '-',
            'description' => $this->description,
            'source_description' => $this->source_description,
            'iban' => $this->iban,
            'mb_entity_code' => $this->mb_entity_code,
            'mb_payment_reference' => $this->mb_payment_reference,
            'photo' => $this->transfer == 1 ? $this->transferWallet->user->photo : '',
        ];
    }
}
