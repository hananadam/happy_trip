<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    public $table = "messages";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];



    public function createMessage($data)
    {
        $msg = new Message();
        $msg->name = $data['name'];
        $msg->email = $data['email'];
        $msg->subject = $data['subject'];
        $msg->message = $data['message'];
        $msg->save();
        return $msg;
    }

}
