<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function regView()
    {
        return view('reg');
    }
    public function register(Request $r)
    {

       $validator = Validator::make($r->all(),[
            'email'=> 'required|string|email:rfc|unique:App\Models\User,email',
           'pass1'=>'required|string|same:pass2',
           'pass2'=>'required|string|same:pass1'
       ]);

       if ($validator->fails()) {
        return response()->json($validator->errors(), 400 );
    }else{
        User::create([
            'email'=>$r->email,
            'password'=>Hash::make($r->pass1),
        ]);
        return response()->json(['auth' => 'success'], 200);
    }
    }

}
