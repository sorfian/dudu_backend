<?php

namespace App\Http\Controllers\API;

use Exception;
use Xendit\Xendit;
use Illuminate\Http\Request;
use App\Models\UserTransaction;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        $transaction = UserTransaction::create([
            'talent_id' => $request->talent_id,
            'user_id' => $request->user_id,
            'total' => $request->total,
            'status' => $request->status,
            'name' => $request->name,
            'moment' => $request->moment,
            'occasion' => $request->occasion,
            'instruction' => $request->instruction,
            'payment_url' => ''
        ]);

        // Konfigurasi midtrans
        // Config::$serverKey = config('services.midtrans.serverKey');
        // Config::$isProduction = config('services.midtrans.isProduction');
        // Config::$isSanitized = config('services.midtrans.isSanitized');
        // Config::$is3ds = config('services.midtrans.is3ds');

        $transaction = UserTransaction::with(['talent','user'])->find($transaction->id);
            return ResponseFormatter::success($transaction,'Transaksi berhasil');


        $midtrans = array(
            'transaction_details' => array(
                'order_id' =>  $transaction->id,
                'gross_amount' => (int) $transaction->total,
            ),
            'customer_details' => array(
                'first_name'    => $transaction->user->name,
                'email'         => $transaction->user->email
            ),
            'enabled_payments' => array('gopay','bank_transfer'),
            'vtweb' => array()
        );

        try {
            // Ambil halaman payment midtrans
            // $paymentUrl = Snap::createTransaction($midtrans)->redirect_url;

            // $transaction->payment_url = $paymentUrl;
            // $transaction->save();

            // // Redirect ke halaman midtrans
            // return ResponseFormatter::success($transaction,'Transaksi berhasil');
        }
        catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(),'Transaksi Gagal');
        }
    }
}
