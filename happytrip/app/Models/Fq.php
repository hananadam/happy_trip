<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fq extends Model
{
    use HasFactory;

    public $table = "fqs";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'topic_id', 'question', 'answer'

    ];

    public function fqtopic()
    {
        return $this->belongsTo(FqTopic::class, 'topic_id', 'id');

    }


}
