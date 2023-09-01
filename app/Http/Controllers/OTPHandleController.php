<?php

namespace App\Http\Controllers;

use App\Mail\optEmail;
use Auth;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Log;
// use Mail;
use Session;
use Twilio\Rest\Client;

class OTPHandleController extends Controller
{
  public function sms($otp,$id)
  {
    try {
      $smsotp=$otp;
      $userid=$id;
      $idoftheuser=user::find($userid);
      $otp_generation_time = Carbon::now();
      if($smsotp===$idoftheuser->otp)
      {
    
        $query = DB::table('users')->where('id', $userid)->update(['otp_generated_at' => $otp_generation_time]); 
        return response()->json(["type"=>"success","message"=>"match","database"=>"$idoftheuser->email_mode"]);
      }
      else
      {
        return response()->json(["type"=>"fail","message"=>"notmatch"]);
      }


     
    } catch (\Throwable $ex) {
      Log::info($ex->getMessage());
    }
  }
  public function email($emailotp,$useremailid)
  {
    $email=$emailotp;
    $emailuserid=$useremailid;
    $userdata=user::find($emailuserid);
    if($email===$userdata->email_otp)
    {
      DB::table('users')->where('id', $emailuserid)->update([ 'email_mode' => "success"]);
      return response()->json(['type'=>"success","message"=>"match","database"=>"$userdata->otp_generated_at"]);
    }
    else
    {
      
      return response()->json(['type'=>"fail","message"=>"not_match"]);
    }

  }
  public function resendsms($useremail_id)
  {
   $idofuser=$useremail_id;
   $data=user::find($idofuser);
   $otp_generation_time=Carbon::now();
   $otpagain=random_int(1000, 9999);
if($data)
      {
        $twilioSid=env('ACCOUNT_SID');
        $twilioToken=env('AUTH_TOKEN');
        $twilioPhoneNumber=env('PHONE_NUMBER');
        $client = new Client($twilioSid, $twilioToken);
       
            $data = $client->messages->create('+91'.$data->contact_number,
                [
                    'from' => $twilioPhoneNumber,
                    'body' => $otpagain
                ]
            );
    
        $query = DB::table('users')->where('id', $idofuser)->update(['otp' => $otpagain]); 
        return response()->json(["type"=>"success","message"=>"match"]);
      }
      else
      {
        return response()->json(["type"=>"fail","message"=>"notmatch"]);
      }
  }

  public function resendemail($useremail_id)
  {
    $idofuser=$useremail_id;
      $useremailotp=random_int(1000, 9999);
    $userdata=user::find($idofuser);
    if($userdata)
    {
      $query=user::where('id',$idofuser)->update(['email_otp'=>$useremailotp]);
      try
      {

        \Mail::to($userdata->email)->send(new optEmail($useremailotp));
        return response()->json(["type"=>"success","message"=>"resendemail"]);
      }
    catch (\Exception $e)
    {
      return response()->json(["type"=>"fail","message"=>"failresendemail"]);
    }
    }

    
    else
    {
      return response()->json(["type"=>"fail","message"=>"failresendemail"]);
    }
  }
  public function store(request $request )
  {
       $theuserid=$request->input('id');
       $queryofuser=user::find($theuserid);
       if($queryofuser)
       {
        $queryofuser->state=2;
        $queryofuser->save();
        return redirect()->route('userDocument')->with(['currentId' => $theuserid, 'contact_number' =>$queryofuser->contact_number , "data" => "success"]);
       }
  }
}