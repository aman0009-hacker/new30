<?php

namespace App\Actions\Fortify;

use App\Mail\optEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Twilio\Rest\Client;
class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;
    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'contact_number' => ['required', "numeric", 'digits:10', Rule::unique(User::class)],
            // 'password' => $this->passwordRules(),
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:6',
                // must be at least 6 characters in length
                'regex:/[a-z]/',
                // must contain at least one lowercase letter
                'regex:/[A-Z]/',
                // must contain at least one uppercase letter
                'regex:/[0-9]/',
                // must contain at least one digit
                'regex:/[@$!%*#?&]/',
                // must contain a special character
            ],
        ])->validate();
        $otp = \Str::random(6);
        // $details=[
        //     'email'=>"OTP VERIFICATOIN CODE",
        //     'message'=>"Your OTP is $otp"
        // ];
        $user = User::create([
            'name' => $input['name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'email_otp'=>random_int(1000, 9999),
            'otp'=>random_int(1000, 9999),
            'contact_number' => $input['contact_number'],
            'password' => Hash::make($input['password']),
            'state' => 1
            //'otp' => $otp,
            //'otp_generated_at' => Carbon::now(),
        ]);
       \Mail::to($user->email)->send(new optEmail($user->email_otp));
       $twilioSid=env('ACCOUNT_SID');
       $twilioToken=env('AUTH_TOKEN');
       $twilioPhoneNumber=env('PHONE_NUMBER');
       $client = new Client($twilioSid, $twilioToken);
      
           $data = $client->messages->create('+91'.$user->contact_number,
               [
                   'from' => $twilioPhoneNumber,
                   'body' => $user->otp
               ]
           );
          
        
        return $user;
    }
}