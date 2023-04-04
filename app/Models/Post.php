<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'title','description','image','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    // protected function created_at():Attribute
    // {
    //     return Attribute::make(
    //         get:fn($value)=>date('d/m/Y',strtotime($value))
    //     );
    // }

    protected $casts=[
        'created_at'=>'datetime:d/m/Y'
    ];

}
