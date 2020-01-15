<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Wallet;

class WalletControllerAPI extends Controller
{
    public function index (Request $request)
    {
        return Wallet::all()->count();
    }

    public function value (Request $request)
    {
        $wallets = Wallet::all();
        $count = 0.00;
        foreach($wallets as $wall){
            $count = $count + $wall->balance;
        }

        return $count;
    }

    public function walletAssociated(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('wallets')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('wallets')->where('email', '=', $request->email)->count();
        }

        if($totalEmail == 1){
            $wallet = Wallet::where('email', $request->email)->first();
            return response()->json($wallet, 200);
        }
        return response()->json(null, 404);
    }


}
