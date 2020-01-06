<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Resources\Movement as MovementResource;

use App\Movement;
use App\Wallet;

class MovementControllerAPI extends Controller
{
    public function index(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);
        return MovementResource::collection($wallet->movements()->orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => ['required', 'numeric'],
            'type' => ['required', 'string', 'max:1'],
            'transfer' => ['required', 'boolean'],
            'transfer_movement_id' => ['nullable', 'numeric'],
            'transfer_wallet_id' => ['nullable', 'numeric'],
            'type_payment' => ['nullable', 'string', 'max:2'],
            'category_id' => ['nullable', 'numeric'],
            'iban' => ['nullable', 'string', 'max:25'],
            'mb_entity_code' => ['nullable', 'numeric', 'digits:5'],
            'mb_payment_reference' => ['nullable', 'numeric', 'digits:9'],
            'description' => ['nullable', 'string'],
            'source_description' => ['nullable', 'string'],
            'start_balance' => ['required', 'numeric'],
            'end_balance' => ['required', 'numeric'],
            'value' => ['required', 'numeric'],
        ]);
        $movement = new Movement();
        $movement->fill($request->all());
        $movement->date = Carbon::now();
        $wallet = Wallet::where('id', $request->wallet_id)->first();
        $wallet->balance = $movement->end_balance;
        $wallet->save();
        $movement->save();
                
        if($movement->transfer == '1'){
            $walletPair = Wallet::where('id', $request->transfer_wallet_id)->first();
            $pair = new Movement();
            $pair->wallet_id = $movement->transfer_wallet_id;
            $pair->type = 'i';
            $pair->transfer = 1;
            $pair->transfer_movement_id = $movement->id;
            $pair->transfer_wallet_id = $movement->wallet_id;
            $pair->category_id = $movement->category_id;
            $pair->source_description = $request->source_description;
            $pair->date = $movement->date;
            $pair->start_balance = $walletPair->balance;
            $pair->value = $movement->value;
            $pair->end_balance = $pair->start_balance + $pair->value;
            $walletPair->balance = $pair->end_balance;
            $walletPair->save();
            $pair->save();
            $movement->transfer_movement_id = $pair->id;
            $movement->save();
        }

        return new MovementResource($movement);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);
        $movement = Movement::findOrFail($id);
        $movement->category_id = $request->category_id;
        $movement->description = $request->description;
        $movement->save();
        return new MovementResource($movement);
    }

}