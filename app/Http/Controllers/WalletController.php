<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(){
        $wallets=Wallet::all();
        return response()->json([
            'status'=>true,
            'msg'=>'All wallets Retrieved Successfully!',
            'data'=>$wallets
        ]);
    }

    public function create(Request $request){
        $rules=$request->validate([
            'amount'=>'required|numeric',
            'user_id'=>'required|numeric'
        ]);
        $userId=$request->input('user_id');
        $user=User::find($userId);
        if(!$user){
            return response()->json([
                'status'=>false,
                'msg'=>'User Not Found!',
            ],404);
        }
        $wallet=Wallet::create($rules);

        return response()->json([
            'status'=>true,
            'msg'=>'Wallet charged successfully!',
            'data'=>$wallet
        ]);

    }

    public function show($id){
        $wallet=Wallet::findOrFail($id);

        //response
        return response()->json([
            'status'=>true,
            'msg'=>'Wallet showed Successfully',
            'data'=>$wallet
        ]);
    }

}
