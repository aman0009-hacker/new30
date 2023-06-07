<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Attachment;
use Carbon\Carbon;
use App\Models\User;

class DocumentProcessController extends Controller
{
  public function ajaxRequestPostDocument(Request $request)
  {
    $currentId = $request->input('txtID');
    $gstNumber = $request->input('gstNumber');
    $msmeNumber = $request->input('msmeNumber');
    $itrNumber = $request->input('itrNumber');
    $adharCardNumber = $request->input('adharCardNumber');
    $panCardNumber = $request->input('panCardNumber');
    $utilityCardNumber = $request->input('utilityCardNumber');
    $request->validate([
      'adharCardNumber' => "required",
      'panCardNumber' => "required"
    ]);
    if (isset($currentId) && $currentId != "") {

      // $query = DB::table('attachments')->insert([
      //     ['fileno' => $gstNumber, 'user_id'=>$currentId , 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now() , 'file_type'=>'gstfile'] ,
      //     ['fileno' => $msmeNumber,  'user_id'=>$currentId, 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now(), 'file_type'=>'msmefile'],
      //     ['fileno' => $itrNumber,  'user_id'=>$currentId, 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now() , 'file_type'=>'itrfile'],
      //     ['fileno' => $adharCardNumber,  'user_id'=>$currentId , 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now() , 'file_type'=>'aadharfile'],
      //     ['fileno' => $panCardNumber, 'user_id'=>$currentId , 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now() , 'file_type'=>'panfile'] ,
      //     ['fileno' => $utilityCardNumber, 'user_id'=>$currentId , 'created_at' => \Carbon\Carbon::now() , 'updated_at'=>\Carbon\Carbon::now() , 'file_type'=>'utilityfile'],
      // ]);

      $data = [
        [
          'fileno' => $gstNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'gstfile'
        ],
        [
          'fileno' => $msmeNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'msmefile'
        ],
        [
          'fileno' => $itrNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'itrfile'
        ],
        [
          'fileno' => $adharCardNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'aadharfile'
        ],
        [
          'fileno' => $panCardNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'panfile'
        ],
        [
          'fileno' => $utilityCardNumber,
          'user_id' => $currentId,
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
          'file_type' => 'utilityfile'
        ]
      ];

      $collectionCount = 0;
      foreach ($data as $entry) {
        $query = new Attachment;
        // $query->fileno = $entry['fileno'] ?? null;
        $query->fileno = ($entry['fileno'] === '' || $entry['fileno'] === null) ? null : $entry['fileno'];
        $query->user_id = $entry['user_id'];
        $query->created_at = $entry['created_at'];
        $query->updated_at = $entry['updated_at'];
        $query->file_type = $entry['file_type'];
        $query->save();
        $collectionCount++;
      }


      if (isset($collectionCount) && $collectionCount > 0) {
        // $stateUpdate = DB::table('users')->where('id', $currentId)->update(['state' => 3]);
        $user = User::where('id', $currentId)->first();
        $user->state = 3;
        $stateUpdate = $user->save();
        if (isset($stateUpdate)) {
          return redirect()->route('documentProcess')->with(['currentId' => $currentId, "data" => "success"]);
        }
      } else {
        return redirect()->back()->withInput();
      }
    }
  }
  public function documentOutput(Request $request)
  {
    // dd("jljlkj");
    return redirect()->route('home')->with(['tab' => "myaccount"]);
  }

}