<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sendSmsController extends Controller
{
    
    public function sendSMS()
    {
        $url=env('PSIEC_Phone_URL');
        $username=env('Phone_USERNAME');
        $password=env('Phone_PASSWORD');
        $senderid=env('Phone_SENDERID');
        $message="hello";
        $mobileno="7986834358";
        $phoneApiType=env('Phone_API_TYPE');
        $phoneOrgId=env('Phone_ORG_ID');
        $deptSecureKey=env('Phone_SECUREKEY ');
        $encryp_password=sha1(trim($password)); 
        // $templateId="1234";

        


        $this->sendOtpSMS($username,$encryp_password,$senderid,$message,$mobileno,$deptSecureKey); 

}
 

public function sendOtpSMS($username,$encryp_password,$senderid,$message,$mobileno,$deptSecureKey){
$key=hash('sha512',trim($username).trim($senderid).trim($message).trim($deptSecureKey));
$data = array(
//  "templateid"=>"1234",
"username" => trim($username),
"password" => trim($encryp_password),
"senderid" => trim($senderid),
"content" => trim($message),
"smsservicetype" =>"otpmsg",
"mobileno" =>trim($mobileno),
"key" => trim($key)
);
$this->post_to_url("https://msdgweb.mgov.gov.in/esms/sendsmsrequestDLT",$data);
//calling post_to_url to send otp sms
}

public function post_to_url($url, $data) {
   
    $fields = '';
    foreach($data as $key => $value) {
    $fields .= $key . '=' . $value . '&';
    }

    // dd($fields);
    rtrim($fields, '&');
    $post = curl_init();
    curl_setopt($post,CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($post); //result from mobile seva server
    dd($result);

    echo $result; //output from server displayed
    curl_close($post);

  
    }

    }

