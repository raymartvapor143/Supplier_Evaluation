<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /**
     * Send password reset link
     */
    public function send(Request $request)
    {

        $request->validate([
            'email'   => ['required', 'email'],
            'captcha' => ['required']
        ]);


        // ============================
        // VERIFY GOOGLE reCAPTCHA
        // ============================

        try {

            $captchaResponse = Http::timeout(10)
                ->asForm()
                ->post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'secret'   => config('services.recaptcha.secret'),
                        'response' => $request->captcha,
                        'remoteip' => $request->ip(),
                    ]
                );


            $captchaResult = $captchaResponse->json();


            if (
                !$captchaResponse->successful()
                ||
                !($captchaResult['success'] ?? false)
            ) {

                return response()->json([
                    'message' =>
                    'Please verify that you are not a robot.'
                ], 422);

            }


        } catch (\Exception $e) {


            return response()->json([
                'message' =>
                'CAPTCHA verification failed. Please try again.'
            ], 500);


        }



        // ============================
        // FIND USER
        // ============================

        $user = User::where(
            'email',
            $request->email
        )->first();



        if (!$user) {

            return response()->json([
                'message' =>
                'Email address not found.'
            ], 404);

        }




        // ============================
        // CREATE RESET TOKEN
        // ============================

        $token = Password::createToken($user);



        $url = route(
            'password.reset',
            [
                'token' => $token,
                'email' => $user->email
            ]
        );




        // ============================
        // SEND EMAIL
        // ============================

        try {


            Mail::send(
                'emails.password-reset',
                [
                    'user' => $user,
                    'url'  => $url
                ],
                function ($message) use ($user) {

                    $message
                        ->to($user->email)
                        ->subject(
                            'Reset Your Password'
                        );

                }
            );


        } catch (\Exception $e) {


            return response()->json([
                'message' =>
                'Unable to send reset email. Please try again later.'
            ], 500);


        }




        return response()->json([

            'message' =>
            'Password reset link sent to your email.'

        ]);

    }




    /**
     * Reset password
     */
    public function reset(Request $request)
    {


        $request->validate([

            'token' =>
            ['required'],

            'email' =>
            ['required','email'],

            'password' =>
            [
                'required',
                'min:8',
                'confirmed'
            ]

        ]);




        $status = Password::reset(

            $request->only(
                [
                    'email',
                    'password',
                    'password_confirmation',
                    'token'
                ]
            ),


            function ($user, $password) {


                $user->forceFill([

                    'password' =>
                    Hash::make($password),


                    'remember_token' =>
                    Str::random(60)

                ])->save();


            }

        );





        if (
            $status === Password::PASSWORD_RESET
        ) {


            return redirect('/login')
                ->with(
                    'success',
                    'Password successfully changed.'
                );


        }





        return back()
            ->withErrors([

                'email' =>
                'Invalid or expired reset token.'

            ]);

    }
}