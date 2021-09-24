<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_transaction_id',
        'type',
        'amount',
        'status'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userTransactions(){
        return $this->hasOne(UserTransaction::class, 'id', 'user_transaction_id');
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
