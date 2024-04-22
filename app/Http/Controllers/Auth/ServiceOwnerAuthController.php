<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\ServiceOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ServiceOwnerAuthController extends Controller
{
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        //the rules of validation are in RegistrationRequest so here we just call the method validated()
        $newServiceOwner=$request->validate([
            'email' => 'required|unique:service_owners|email|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'role'=>'required'
        ]);

        // Check if the role is 1
        if ($newServiceOwner['role'] != 1) {
            return response()->json([
                'status' => false,
                'msg' => 'Invalid role value. Role must be 1.',
                'data' => null
            ], 400);
        }
        //taking the password from the FRONT END and then hash it for security
        $newServiceOwner['password']=Hash::make($newServiceOwner['password']);
        $newServiceOwner['role']=$request->input('role');


        $user=ServiceOwner::create($newServiceOwner);
        //creating a token for the newUser and send it with the name in the response
        $success['token']=$user->createToken('service_owners',['app:all'])->plainTextToken;
        $success['name']=$user->name;
        $success['role']=$user->role;
        $success['serviceOwner_id']=$user->id;


        return response()->json([
            'status'=>true,
            'msg'=>'Registered successfully',
            'data'=>$success,
        ],200);

    }

    public function login(Request $request){
        //validation
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        //check serviceOwner
        $serviceOwner=ServiceOwner::where('email', '=' ,$request->email)->first();
        if(isset($serviceOwner->id)){
            if(Hash::check($request->password,$serviceOwner->password)){
                //create a token
                $token=$serviceOwner->createToken('auth_token')->plainTextToken;
                $success['token']=$token;
                $success['role']=1;
                //send a response
                return response()->json([
                    'status'=>true,
                    'msg'=>"Logged in successfully",
                    'data'=>$success
                ],200);
            }
            else{
                return response()->json([
                    'status'=>false,
                    'msg'=>"Password does not match"
                ],404);
            }

        }else{
            return response()->json([
                'status'=>false,
                'msg'=>"service owner not found"
            ],404);
        }

    }
    public function profile(Request $request){

        return response()->json([
            'status'=>true,
            'msg'=>'Service Owner Profile Information',
            'data'=>auth()->user()
        ]);
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>true,
            'msg'=>'Service Owner Logged Out Successfully',
            'role'=>1
        ]);
    }

}

