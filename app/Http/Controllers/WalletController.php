<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(){
        $wallets=Wallet::all();
        $responseData=[];
        foreach($wallets as $wallet) {
        $subData=[];
        $user=User::find($wallet->user_id);
            $subData['wallet_id'] = $wallet->id;
            $subData['user_id'] = $user->id;
            $subData['user_name'] = $user->name;
            $subData['amount'] = $wallet->amount;

        $responseData[]=$subData;
        }
        return response()->json([
            'status'=>true,
            'msg'=>'All wallets Retrieved Successfully!',
            'data'=>$responseData
        ],200);
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

    public function update(Request $request,$id)
    {
        //validation
        $rules=$request->validate([
            'amount'=>'required|numeric',
        ]);

        // Find the wallet by ID
        $wallet = Wallet::find($id);
        if(!$wallet){
            return response()->json([
                'status'=>false,
                'msg'=>'Wallet Not Found!'
            ]);
        }

        // Update the wallet attributes
        $wallet->amount = $rules['amount'];


        // Save the updated wallet
        $wallet->save();

        $user=User::find($wallet->user_id);

        // Response array
        $success['amount'] = $wallet->amount;
        $success['user_name']=$user->name;

        // Response
        return response()->json([
            'status' => true,
            'msg' => 'Flight Updated Successfully',
            'data' => $success
        ]);
    }

    public function show($id){
        $wallet=Wallet::find($id);
        if(!$wallet){
            return response()->json([
                'status'=>false,
                'msg'=>'Wallet Not Found!'
            ]);
        }

        //response
        return response()->json([
            'status'=>true,
            'msg'=>'Wallet showed Successfully',
            'data'=>$wallet
        ]);
    }

}
