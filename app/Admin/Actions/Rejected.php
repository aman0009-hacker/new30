<?php

namespace App\Admin\Actions;

use App\Models\User;
use Encore\Admin\Actions\RowAction;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Facades\Admin;
use Carbon\Carbon;
use App\Models\Comments;
use Illuminate\Support\Facades\Log;


class Rejected extends RowAction
{
    public $name = 'Rejected';
    public function form()
    {
        $this->textarea('reason', 'Reason')->rules('required');
    }
    public function handle(Model $model, Request $request)
    {
        try {
            // Get the `reason` value in the form
            $userid = $model->id;
            $adminid = Admin::user()->id;
            $user = User::find($userid);
            $user->approved = 2;
            $user->save();
            $comment = new Comments;
            $comment->user_id = $userid;
            $comment->admin_id = $adminid;
            $comment->comment = $request->get('reason') ?? '';
            $comment->commented_by = "admin";
            $comment->username = Admin::user()->username;
            $comment->save();
            // Your reporting logic...
            if ($user) {
                // $emailData = DB::table('users')->join('attachments', 'users.id', '=', 'attachments.user_id')->where('attachments.user_id', $model->id)->select('users.email')->first();
                $emailData = User::join('attachments', 'users.id', '=', 'attachments.user_id')
                    ->where('attachments.user_id', $model->id)
                    ->select('users.email')
                    ->first();
                if (isset($emailData)) {
                    $emailDataName = $emailData->email;
                    $details = [
                        'email' => 'Account Verification Status - Action Required',
                        // 'body' => 'Your account has not verified due to issue in your documents' . "  " . $request->get('reason') ?? ''
                        'body' => 'We regret to inform that your account verification process could not be completed due to issues in the provided documents:' . "  " . $request->get('reason') ?? ''
                    ];
                    \Mail::to($emailDataName)->send(new \App\Mail\PSIECMail($details));
                    //\mail::to('csanwalit@gmail.com')->send(new \App\Mail\PSIECMail($details));
                } else {
                    return $this->response()->error('Oops! Kindly submit documents as required');
                }
            }
            return $this->response()->success('User status has successfully changed')->refresh();
        } catch (\Throwable $ex) {
            //return $this->response()->error('Oops! Sending mail has encountered some internal problem');
            Log::info($ex->getMessage());
        }
    }
}