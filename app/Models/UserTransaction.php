<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'talent_id',
        'total',
        'status',
        'payment_url',
        'name',
        'moment',
        'birthday_date',
        'age',
        'occasion',
        'instruction',
        'detail'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function talent(){
        return $this->hasOne(Talent::class, 'id', 'talent_id');
    }

    public function order(){
        return $this->belongsTo(Order::class, 'user_transaction_id', 'id');
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
