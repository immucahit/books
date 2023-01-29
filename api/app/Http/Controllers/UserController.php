<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        $user = User::where('username',$request->input('username'))->first();
        if(Hash::check($request->input('password'),$user->password)){
            return response()
                ->json([
                    'access_token' => JWT::encode([
                        'user' => $user->id,
                        'iss' => config('app.url'),
                        'iat' => now()->getTimestamp(),
                        'exp' => now()->addDay()->getTimestamp()
                    ], env('JWT_KEY'), 'HS256')
                ]);
        }else{
            return response()
                ->json([
                    'message' => 'Kullanıcı adı veya parola hatalı!'
                ],400);
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'displayName' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()
                ->json([
                    'message' => 'Lütfen tüm alanları doldurunuz!'
                ],400);
        }

        if(User::where('username',$request->input('username'))->exists()){
            return response()
                ->json([
                    'message' => 'Lütfen farklı bir kullanıcı adı seçiniz!'
                ],400);
        }

        return response()
            ->json(new UserResource(User::create([
                'display_name' => $request->input('displayName'),
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password'))
            ])));
    }
}
