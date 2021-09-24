<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Talent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'price',
        'picture_path'
    ];

    public function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userTransactions(){
        return $this->belongsToMany(UserTransaction::class, 'talent_id', 'id');
    }

    public function review(){
        return $this->belongsToMany(Review::class, 'talent_id', 'id');
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
