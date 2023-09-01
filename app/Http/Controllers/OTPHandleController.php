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

class OTPHandleController extends Controller
{
  public function sms($otp,$id)
  {
    try {
      $smsotp=$otp;
      $userid=$id;
      $idoftheuser=user::find($userid);
      $otp_generation_time = Carbon::now();
      if($smsotp==="1234")
      {
    
        $query = DB::table('users')->where('id', $userid)->update(['otp' => $smsotp, 'otp_generated_at' => $otp_generation_time, 'state' => 2]); 
        return response()->json(["type"=>"success","message"=>"match","database"=>"$idoftheuser->email_mode"]);
      }
      else
      {
        return response()->json(["type"=>"fail","message"=>"notmatch"]);
      }


      // $contact_number = $request->input('contact_number');
      // $userOtp = $request->input('userOtp');
      // $id = $request->input('id');
      // $emailotp=$request->input('useremailOtp');
      // $otp_generation_time = Carbon::now();
      // if ($userOtp == "1234") {
      //   if (isset($contact_number) && !empty($contact_number) && isset($userOtp) && !empty($userOtp)) {
      //     // $query = DB::table('users')->where('id', $id)->update(['contact_number' => $contact_number, 'otp' => $userOtp, 'otp_generated_at' => $otp_generation_time, 'state' => 2]);
      //     $query = User::find($id);
      //     $collectionCount = 0;
      //     if ($query) {
      //       $query->contact_number = $contact_number;
      //       $query->otp = $userOtp;
      //       $query->otp_generated_at = $otp_generation_time;
      //       $query->state = 2;
      //       $query->save();
      //       $collectionCount++;
      //     }
      //     if (isset($collectionCount) && $collectionCount > 0) {
      //       return redirect()->route('userDocument')->with(['currentId' => $id, 'contact_number' => $contact_number, "data" => "success"]);
      //     }
      //   }
      // } else {
      //   return redirect()->route('signUpSubmit')->with(['currentId' => $id, 'contact_number' => $contact_number, "data" => "notsuccess"]);
      // }
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
      DB::table('users')->where('id', $emailuserid)->update([ 'email_mode' => "success", 'state' => 2]);
      return response()->json(['type'=>"success","message"=>"match","database"=>"$userdata->otp_generated_at"]);
    }
    else
    {
      
      return response()->json(['type'=>"fail","message"=>"not_match"]);
    }

  }
  // public function resendsms($useremail_id)
  // {
  //  $idofuser=$useremail_id;
  //  $data=user::find($idofuser);
  //  if($smsotp==="1234")
  //     {
    
  //       $query = DB::table('users')->where('id', $userid)->update(['otp' => $smsotp, 'otp_generated_at' => $otp_generation_time, 'state' => 2]); 
  //       return response()->json(["type"=>"success","message"=>"match"]);
  //     }
  //     else
  //     {
  //       return response()->json(["type"=>"fail","message"=>"notmatch"]);
  //     }
  // }

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
}