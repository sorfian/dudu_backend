<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Complain extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'description',
        'status'
    ];

    public function order(){
        return $this->hasOne(Order::class, 'id', 'order_id');
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
