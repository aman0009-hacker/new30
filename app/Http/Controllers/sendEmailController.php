<?php

namespace App\Http\Controllers;

use App\Mail\optEmail;
use Illuminate\Http\Request;

class sendEmailController extends Controller
{
    public function emailotp(Request $request )

    {
        $otp=random_int(1000, 9999);
        $content=[
            'body'=>"PSIEC ADMIN PANEL",
            'message'=>"Your OTP is $otp"
        ];
        $email=$request->input('email');
        \Mail::to($email)->send(new optEmail($content));
    }
}
