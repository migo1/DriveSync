<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\LoginNeedsVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function submit(Request $request)
    {

        
        // validate the phone number
        $request->validate([
           //  'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
           'phone' => 'required|numeric|min:10'
        ]);

        // find or create a user model

        $user = User::firstOrCreate(
            ['phone' => $request->phone]
        );
// dd($user);

        if(!$user) {
            return response()->json([
                'message' => 'Could not process a user with that phone number.'
            ], 401);
        }
        //send the user a one-time code
        $user->notify(new LoginNeedsVerification());
        // return back a response

        return response()->json([
            'message' => 'Login code sent.'
        ]);
    }

    public function verify(Request $request)
    {
        //validate the incoming request

        $request->validate([
            //  'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10'
            'phone' => 'required|numeric|min:10',
            'login_code' => 'required|numeric|between:111111,999999'
         ]);


        //find user

        $user = User::where('phone', $request->phone)
            ->where('login_code', $request->login_code)
        ->first();

        // is code provided as the same one saved?
        if($user){
            // if so, log in the user/ return back to the auth token
            $user->update([
                'login_code' => null
            ]);
            return $user->createToken($request->login_code)->plainTextToken;

            // return response()->json([
            //     'access_token' => $token,
            //     'token_type' => 'Bearer',
            // ]);
        }

        // if so, log in the user/ return back to the auth token

        // if not, return back an error message

        return response()->json([
            'message' => 'Invalid login code provided.'
        ], 401);

    }
}
