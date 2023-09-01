<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class OrderProcessController extends Controller
{
    public function index(Request $request)
    {
        $txtOrderGlobalModalID = $request->input('txtOrderGlobalModalID');
        Session::forget('txtOrderGlobalModalID');
        Session::put('txtOrderGlobalModalID', $txtOrderGlobalModalID ?? '');
        if (isset($txtOrderGlobalModalID) && !empty($txtOrderGlobalModalID)) {
            return view('components.order-process', compact('txtOrderGlobalModalID'));
            //dd($txtOrderGlobalModalID=$request->input('txtOrderGlobalModalID'));
        }
    }

    public function emailinitialpayment($id)
    {
        $txtOrderGlobalModalID=Crypt::decrypt($id);
        Session::forget('txtOrderGlobalModalID');
        Session::put('txtOrderGlobalModalID', $txtOrderGlobalModalID ?? '');
        if (isset($txtOrderGlobalModalID) && !empty($txtOrderGlobalModalID)) {
            return view('components.order-process', compact('txtOrderGlobalModalID'));
            
        }
    }

    public function jspart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'files' => 'required|array', // Ensure that 'files' is an array and not empty
                'files.*' => 'file|mimes:jpeg,png,pdf|max:5120', // Validate each file in the 'files' array
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $allfiles = $request->file('files');
            if (isset($allfiles) && !empty($allfiles) && $request->hasFile('files')) {
                $modalIdInput = $request->input('modalId');
                // Find the Order model based on $modalIdInput
                $order = Order::findOrFail($modalIdInput);
                $num = 0;
                foreach ($allfiles as $image) {
                    //$files = time() . "." . $image->getClientOriginalExtension();   
                    $files = time() . rand(11, 99) . '.' . $image->getClientOriginalName();
                    $image->move(public_path('uploads'), $files);
                    if ($num == 0) {
                        $order->upload = $files;
                        $num++;
                    } else if ($num == 1) {
                        $order->file1 = $files;
                        $num++;
                    } else {
                        $order->file2 = $files;
                    }
                }
                $order->check_amount = $request->amount;
                $order->cheque_number = $request->cheque;
                $order->Cheque_Date = $request->chequedate;
                $order->save();
                return redirect()->back();
            }
        } catch (\Throwable $ex) {
            dd($ex->getMessage());
            Log::info($ex->getMessage());
        }
    }
}