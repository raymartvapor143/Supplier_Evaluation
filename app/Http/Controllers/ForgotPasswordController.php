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
     * Generate puzzle captcha parameters
     */
    public function getPuzzle()
    {
        $token = Str::random(32);
        $targetX = rand(60, 210);
        $targetY = rand(25, 80);
        $seed = rand(1000, 9999);

        session(['puzzle_captcha_' . $token => [
            'x' => $targetX,
            'y' => $targetY
        ]]);

        return response()->json([
            'token' => $token,
            'target_x' => $targetX,
            'target_y' => $targetY,
            'seed' => $seed
        ]);
    }

    /**
     * Send password reset link
     */
    public function send(Request $request)
    {

        $request->validate([
            'email'         => ['required', 'email'],
            'captcha_token' => ['required'],
            'captcha_x'     => ['required', 'numeric']
        ]);


        // ============================
        // VERIFY PUZZLE CAPTCHA
        // ============================

        $puzzleData = session('puzzle_captcha_' . $request->captcha_token);

        if (!$puzzleData || !isset($puzzleData['x'])) {
            return response()->json([
                'message' => 'CAPTCHA session expired. Please refresh the puzzle and try again.'
            ], 422);
        }

        $expectedX = (int) $puzzleData['x'];
        $userX = (int) $request->captcha_x;

        if (abs($expectedX - $userX) > 8) {
            return response()->json([
                'message' => 'Puzzle piece position is incorrect. Please align the puzzle correctly.'
            ], 422);
        }

        session()->forget('puzzle_captcha_' . $request->captcha_token);



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

            \Illuminate\Support\Facades\Log::error('Password reset email error: ' . $e->getMessage());

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