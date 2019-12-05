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
}