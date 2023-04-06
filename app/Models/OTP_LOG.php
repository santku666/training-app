<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OTP_LOG extends Model
{
    use HasFactory,SoftDeletes;

    protected $table="otp_logs";
    protected $fillable=[
        "mode","code","user_id","expired_at"
    ];
    
}
