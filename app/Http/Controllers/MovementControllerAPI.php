<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => ['nullable', 'numeric'],
            'description' => ['nullable', 'string'],
        ]);
        $movement = Movement::findOrFail($id);
        $movement->category_id = $request->category_id;
        $movement->description = $request->description;
        $movement->timestamps = false;
        $movement->save();
        return new MovementResource($movement);
    }

}