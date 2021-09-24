<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\UserTransaction;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
}
