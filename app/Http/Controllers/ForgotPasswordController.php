<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;


class ForgotPasswordController extends Controller
{

    public function send(Request $request)
    {

        $request->validate([
            'email'=>'required|email'
        ]);


        $user = User::where(
            'email',
            $request->email
        )->first();


        if(!$user){

            return response()->json([
                'message'=>'Email address not found.'
            ],404);

        }


        $token = Password::createToken($user);


$url = route('password.reset', [
   'token' => $token,
    'email' => $user->email
]);


        Mail::send(
            'emails.password-reset',
            [
                'user'=>$user,
                'url'=>$url
            ],
            function($message) use ($user){

                $message
                ->to($user->email)
                ->subject('Reset Your Password');

            }
        );


        return response()->json([
            'message'=>'Password reset link sent to your email.'
        ]);

    }



    public function reset(Request $request)
    {

        $request->validate([

            'token'=>'required',

            'email'=>'required|email',

            'password'=>'required|min:8|confirmed'

        ]);



        $status = Password::reset(

            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function($user,$password){

                $user->forceFill([

                    'password'=>bcrypt($password),

                    'remember_token'=>Str::random(60)

                ])->save();

            }

        );



        if($status == Password::PASSWORD_RESET){

            return redirect('/login')
            ->with(
                'success',
                'Password successfully changed.'
            );

        }


        return back()
        ->withErrors([
            'email'=>'Invalid reset token.'
        ]);

    }

}
