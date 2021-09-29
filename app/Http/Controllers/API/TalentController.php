<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Talent;
use Illuminate\Http\Request;

class TalentController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $type = $request->input('type');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $rate_from = $request->input('rate_from');
        $rate_to = $request->input('rate_to'); 

        if ($id) {
            $talent = Talent::find($id);

            if ($talent) {
                return ResponseFormatter::success(
                    $talent,
                    'Data talent berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data talent tidak ada',
                    404
                );
            }
            
        } 
        
        $talent = Talent::with(['user']);
        // $talent = Talent::query();

        if ($name) {
            $talent->where('name', 'like', '%' . $name . '%');
        }
        if ($type) {
            $talent->where('type', 'like', '%' . $type . '%');
        }
        if ($price_from) {
            $talent->where('price_from', '>=', $price_from);
        }
        if ($price_to) {
            $talent->where('price_to', '<=', $price_to);
        }
        if ($rate_from) {
            $talent->where('rate_from', '>=', $rate_from);
        }
        if ($rate_to) {
            $talent->where('rate_to', '<=', $rate_to);
        }

        return ResponseFormatter::success(
            $talent->paginate($limit),
            'Data list talent berhasil diambil'
        );
        
    }
}
