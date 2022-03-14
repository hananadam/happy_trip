<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FqTopic extends Model
{
    use HasFactory;

    public $table = "fqs_topics";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title'

    ];

    public function fqs()
    {
        return $this->hasMany('App\Models\Fq', 'topic_id');
    }

}
