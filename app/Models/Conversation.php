<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function message() {
    	return $this->hasMany(Message::class);
    }

    public function sender() {
    	return $this->belongsTo(User::class, 'from_id');
    }

    public function reciever() {
    	return $this->belongsTo(User::class, 'to_id');
    }

    public function createConversation(Request $request){
        $this->from_id = auth()->user()->id;
        $this->to_id = $request->reciever_id;
        $this->save();
        return $this;
    }
}
