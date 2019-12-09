<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Wallet;

class WalletControllerAPI extends Controller
{
    public function index (Request $request)
    {
        return Wallet::all()->count();
    }
}
