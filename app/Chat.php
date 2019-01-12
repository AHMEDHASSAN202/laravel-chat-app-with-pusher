<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['user_id_1', 'user_id_2', 'message'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id_1', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'user_id_2', 'id');
    }

    public static function messages($senderID, $receiveID)
    {
        return self::with('sender')->with('receiver')->where([
                        ['user_id_1', '=', $senderID],
                        ['user_id_2', '=', $receiveID]
                    ])
                    ->orWhere([
                        ['user_id_2', '=', $senderID],
                        ['user_id_1', '=', $receiveID]
                    ])->get();
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
