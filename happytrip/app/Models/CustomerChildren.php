<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerChildren extends Model
{
    public $table = "customer_children";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'age'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
