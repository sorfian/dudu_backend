<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_transaction_id',
        'status',
        'video_path'
    ];

    public function userTransactions(){
        return $this->hasOne(UserTransaction::class, 'id', 'user_transaction_id');
    }

    public function complain(){
        return $this->belongsTo(Complain::class, 'order_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->timestamp;
    }
}
