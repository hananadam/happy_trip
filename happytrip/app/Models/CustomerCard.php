<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerCard extends Model
{
    public $table = "customer_cards";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'number',
        'expire_month',
        'expire_year',
        'cvv',
        'address'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class,'id','payable_id');
    }

 
}
