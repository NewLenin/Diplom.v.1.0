<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function authView()
    {
        return view('login');
    }

    public function auth(Request $r){
        $validator = Validator::make($r->all(),[
            'email'=> 'required|string|',
            'password'=>'required|string|',

        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400 );
        }
        if (Auth::attempt(['email' => $r->email, 'password' => $r->password])) {
            return response()->json(['register' => 'success'], 200);
        }else{
            return response()->json(['errors'=>['form'=>'Ошибка авторизации! Неверный логин или пароль']], 401 );
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('home');
    }
}
