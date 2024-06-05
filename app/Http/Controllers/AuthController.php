<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name'=>'required|string|max:100',
            'email'=>'required|string|email|max:100|unique:users',
            'password'=>'required|string|min:8',
        ];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all()
            ],400);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'User Created succefully',
            'token'=>$user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function login(Request $request)
    {
        $rules = [
            'email'=>'required|string|email|max:100',
            'password'=>'required|string|min:8',
        ];
        $validator = \Validator::make($request->input(),$rules);
        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()->all()
            ],400);
        }

        if(!Auth::attempt($request->only(['email','password'])))
        {
            return response()->json([
                'status'=>false,
                'error'=>['Unauthorized']
            ],401);
        }

        $user = User::where('email',$request->email)->first();
        return response()->json([
            'status'=>true,
            'message'=>'User Logged in succefully',
            "data"=>$user,
            'token'=>$user->createToken('API TOKEN')->plainTextToken
        ],200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status'=>true,
            'message'=>'User Logged out succefully',
        ],200);
    }
}
