<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserTransaction;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    public function callback(Request $request)
    {
        $externalId = 'invoice_123124123';
        $xenditId = '579c8d61f23fa4ca35e52da4';
        $paymentMethod = $request->payment_method;
        $status = $request->status;
        $amount = $request->amount;
        $paymentChannel = $request->payment_channel;

        try {
            $transaction = UserTransaction::where('external_id', $externalId)->where('invoice_number', $xenditId)->first();
            if ($status == 'PAID') {
                $transaction->status = 'SUCCESS';
            } else if ($status == 'EXPIRED') {
                $transaction->status == 'CANCELLED';
            } else {
                $transaction->status == 'ON PROCESS';
            }

            $transaction->payment_method = $paymentMethod;
            $transaction->payment_channel = $paymentChannel;
            $transaction->total = $amount;
            $transaction->save();
            
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'message' => 'Xendit payment status successfully saved!'
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'meta' => [
                    'code' => 500,
                    'message' => $th,
                ]
            ], 500);
        }
        
    }
}
