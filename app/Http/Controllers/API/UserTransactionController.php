<?php

namespace App\Http\Controllers\API;

use Exception;
use Xendit\Xendit;
use App\Models\User;
use App\Models\Talent;
use Illuminate\Http\Request;
use App\Models\UserTransaction;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

Xendit::setApiKey('xnd_development_6PaoSfikDvNDQqnXGGn0wZidfx8AsHMUhxCXQZswt8drq2XNdq3LpHJ3SCxAiTA');
class UserTransactionController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $talent_id = $request->input('talent_id');
        $status = $request->input('status');

        if ($id) {
            $userTransaction = UserTransaction::with(['talent', 'user', 'order'])->find($id);
            $user = User::where('id', $userTransaction['talent']['user_id'])->first();
        $userTransaction['talent']['talent_name'] = $user->name;
        $userTransaction['talent']['talent_email'] = $user->email;

            if ($userTransaction) {
                return ResponseFormatter::success(
                    $userTransaction,
                    'Data transaksi user berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi user tidak ada',
                    404
                );
            }
            
        } 
        
        $userTransaction = UserTransaction::with(['talent', 'user', 'order'])->where('user_id', Auth::user()->id);
        $talent = Talent::where('user_id', Auth::user()->id)->first();


        if ($talent_id) {
            if ($talent) {
                
                $userTransaction = UserTransaction::with(['talent', 'user', 'order'])->where('talent_id', $talent->id)->where('status', 'PAID');
            }
        }
        if ($status) {
            $userTransaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $userTransaction->paginate($limit),
            'Data list transaksi user berhasil diambil'
        );
        
    }
    public function talentTransactions(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $talent_id = $request->input('talent_id');
        $status = $request->input('status');

        if ($id) {
            $userTransaction = UserTransaction::with(['talent', 'user', 'order'])->find($id);
            $user = User::where('id', $userTransaction['talent']['user_id'])->first();
        $userTransaction['talent']['talent_name'] = $user->name;
        $userTransaction['talent']['talent_email'] = $user->email;

            if ($userTransaction) {
                return ResponseFormatter::success(
                    $userTransaction,
                    'Data transaksi user berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data transaksi user tidak ada',
                    404
                );
            }
            
        } 

        $talent = Talent::where('user_id', Auth::user()->id)->first();
        
        $userTransaction = UserTransaction::with(['talent', 'user', 'order'])->where('user_id', Auth::user()->id);

        if ($talent_id) {
            $userTransaction->where('talent_id', $talent_id);
        }
        if ($status) {
            $userTransaction->where('status', $status);
        }

        return ResponseFormatter::success(
            $userTransaction->paginate($limit),
            'Data list transaksi user berhasil diambil'
        );
        
    }

    public function update(Request $request, $id) {
        $transaction = UserTransaction::findOrFail($id);

        $transaction->update($request->all());

        return ResponseFormatter::success($transaction, 'Transaksi berhasil di-update');
    }

    public function checkout(Request $request) {

        // $getBalance = \Xendit\Balance::getBalance('CASH');
        // return $getBalance;

        $request->validate([
            'talent_id' => 'required|exists:talent,id',
            'user_id' => 'required|exists:users,id',
            'total' => 'required',
            'status' => 'required',
            'name' => 'required|string',
            'moment' => 'required|string',
            'occasion' => 'required|string',
            'instruction' => 'required|string',
        ]);
        
        $invoiceNumber = 'invoice_'.time();

        $transaction = UserTransaction::create([
            'talent_id' => $request->talent_id,
            'user_id' => $request->user_id,
            'total' => $request->total,
            'external_id' => $invoiceNumber,
            'status' => $request->status,
            'name' => $request->name,
            'moment' => $request->moment,
            'occasion' => $request->occasion,
            'instruction' => $request->instruction,
            'payment_url' => ''
        ]);

        $transaction = UserTransaction::with(['talent','user'])->find($transaction->id);
        $user = User::where('id', $transaction['talent']['user_id'])->first();
        $transaction['talent']['talent_email'] = $user->email;

            $params = [
                'external_id' => $invoiceNumber,
                'payer_email' => Auth::user()->email,
                'description' => $request->moment,
                'amount' => $request->total,
                'customer' => [
                    'given_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    // 'mobile_number' => Auth::user()->phone_number,
                ],
                'customer_notification_preference' => [
                    'invoice_created' => ["whatsapp", "sms", "email"],
                    'invoice_reminder' => ["whatsapp", "sms", "email"],
                    'invoice_paid' => ["whatsapp", "sms", "email"],
                    'invoice_expired' => ["whatsapp", "sms", "email"]
                ],
            ];

        try {

            $createInvoice = \Xendit\Invoice::create($params);
            // var_dump($createInvoice);

            $id = $createInvoice['id'];
            $invoice_url = $createInvoice['invoice_url'];
            $status = $createInvoice['status'];
            $amount = $createInvoice['amount'];

            $transaction->invoice_number = $id;
            $transaction->payment_url = $invoice_url;
            $transaction->status = $status;
            $transaction->total = $amount;
            $transaction->save();

            return ResponseFormatter::success($transaction,'Transaksi berhasil');

        }
        catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),'Transaksi Gagal');
        }
    }

    public function uploadVideo(Request $request) {
        $validator = Validator::make($request->all(), [
            'external_id' => 'required|string',
            'video_file' => 'nullable|file',
            'video_thumbnail' => 'nullable|image|max:5000',
            'video_file_talent' => 'nullable|file',
            'video_thumbnail_talent' => 'nullable|image|max:5000'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors()
            ], 'Upload video fails', 401);
        }

        $transaction = UserTransaction::where('external_id', $request->external_id)->first();

        try {
            if ($request->file('video_file')) {
                $videoFile = $request->video_file->store('assets/user', 'public');
                $transaction->video_file = $videoFile;
            }
    
            if ($request->file('video_thumbnail')) {
                $videoThumbnail = $request->video_thumbnail->store('assets/user', 'public');
                $transaction->video_thumbnail = $videoThumbnail;
    
            }
            if ($request->file('video_file_talent')) {
                $videoFileTalent = $request->video_file_talent->store('assets/user', 'public');
                $transaction->video_file_talent = $videoFileTalent;
                $transaction->status = $request->status;
            }
    
            if ($request->file('video_thumbnail_talent')) {
                $videoThumbnailTalent = $request->video_thumbnail_talent->store('assets/user', 'public');
                $transaction->video_thumbnail_talent = $videoThumbnailTalent;
    
            }
    
            $transaction->update();
    
            return ResponseFormatter::success([$videoFile ?? null, $videoThumbnail ?? null, $videoFileTalent ?? null, $videoThumbnailTalent ?? null], 'File successfully uploaded');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),'Upload video Gagal');
        }
        

    }

    
}
