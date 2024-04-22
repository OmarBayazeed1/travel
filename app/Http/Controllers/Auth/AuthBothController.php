<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ServiceOwner;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthBothController extends Controller
{
    //login Both
    public function loginBoth(Request $request){
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the email belongs to a service owner
        $serviceOwner = ServiceOwner::where('email', '=', $request->email)->first();
        if (isset($serviceOwner->id)) {
            if (Hash::check($request->password, $serviceOwner->password)) {
                // Create a token
                $token = $serviceOwner->createToken('auth_token')->plainTextToken;
                $success['token'] = $token;
                $success['role'] = '1';
                $success['serviceOwner_id']=$serviceOwner->id;

                return response()->json([
                    'status' => true,
                    'msg' => 'Logged in successfully as a service owner',
                    'data' => $success,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'Password does not match',
                ], 404);
            }
        }

        // Check if the email belongs to a user
        $user = User::where('email', '=', $request->email)->first();
        if (isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                // Create a token
                $token = $user->createToken('auth_token')->plainTextToken;
                $success['token'] = $token;
                $success['role'] = '2';
                $success['user_id']=$user->id;

                return response()->json([
                    'status' => true,
                    'msg' => 'Logged in successfully as a user',
                    'data' => $success,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'msg' => 'Password does not match',
                ], 404);
            }
        }

        return response()->json([
            'status' => false,
            'msg' => 'User not found',
        ], 404);
    }



    public function logoutBoth()
    {
        $guards = ['user', 'serviceOwner'];
        $roles=[];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                $role = (string) $user->role;
                $user->tokens()->each(function ($token) {
                    $token->delete();
            });
                $roles[$guard]=$role;
        }
        }
        return response()->json([
            'status' => true,
            'msg' => 'Logged out successfully.',
            'role'=>$role
        ]);
    }

    public function profileBoth(){

        $user=auth()->user();
        // Convert role to a string
        $user->role = strval($user->role);
        $wallet=Wallet::find($user->id);

        //response array
        $success['user_name']=$user->name;
        $success['user_id']=$user->id;
        $success['email']=$user->email;
        $success['role']=$user->role;
        if($user->role==2){
            $success['wallet_amount']=$wallet->amount;
        }

        return response()->json([
            'status' => true,
            'msg' => 'profile retrieved successfully.',
            'data'=>$success
        ]);

    }


}
