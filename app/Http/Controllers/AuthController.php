<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
            'email' => 'email | required | string | unique:users',
            'password' => 'required | string | min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        } else {



            $validatedData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ];
            $user=User::create($validatedData);
            if($user){
                return response()->json(["status" => true, "message" => "User Registered","user" => $validatedData], 201);
            }
            else{
                return response()->json(["status" => false, "message" => "Failed To Register"],422);

            }
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email | required | string ',
            'password' => 'required | string'
        ]);
        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()], 422);
        }
        else{
            if (Auth::attempt($request->all())) {
                $user = Auth::user();
                $token = JWTAuth::fromUser($user);
            
                return response()->json(['status'=>true,"message"=>"Logged In","token"=>$token]);
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
    }
    public function getDetails(){
        $user = Auth::user();
        return response()->json(['status'=>true,"details"=>$user]);
    }
    public function logout()
    {
    }
   
    
}
