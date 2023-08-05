<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRegisterRequest;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRegisterRequest $request)
    {   
        try{

            $user = User::create([
                'firstname' => $request->input('firstname'),
                'lastname'  => $request->input('lastname'),
                'role'      => $request->input('role'),
                'email'     => $request->input('email'),
                'password'  => Hash::make($request->input('password'))
            ]);

            $token = $user->createToken('user_token')->plainTextToken;
            return response()->json(['success'=> true, 'token' => $token], 200);

        }catch (\Exception $e){
            return response()->json([
                'message' => 'something went wrong when registering',
                'error', 'message' => $e->getMessage()
            ]);
        }
    }

    public function login(Request $request){

            $email = $request->input('email');
            $pass  = $request->input('password');
            
            $validatedData = $this->ValidateLogin($email, $pass);

            if($validatedData['success'] == true){
                $user = $validatedData['user'];

                $token = $user->createToken('user_token')->plainTextToken;

                return response()->json([
                    'message' => 'you have successfully logged in',
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'role' => $user->role,
                    'email' => $user->email,
                    'token' => $token,
                    'success'=> true
                ], 200);
            } else{
                return response()->json([
                    'email_error' => $validatedData['email_error'],
                    'password_error' => $validatedData['password_error'],
                    'success'=> false
                ], 401);
            }

        
    }

    public function ValidateLogin($email, $password){

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $result = [
            'user' => null,
            'success'=> false
        ];

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $email)->first();
            $result['user'] = $user;
            $result['success'] = true;
        }

        return $result;
    }

    public function logout(Request $request){
        try{
            $user = User::findOrFail($request->input('user_id'));

            $user->tokens()->delete();
            return response()->json([
                'message' => 'you have successfully logged out',
                'success'=> true
            ], 200);

        } catch (\Exception $e){
            return response()->json([
                'message' => 'something went wrong when logging out',
                'error', 'message' => $e->getMessage()
            ]);
        }
    }
}
