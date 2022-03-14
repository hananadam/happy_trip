<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    public $table = "ratings";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'rate',
        'comment'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
