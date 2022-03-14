<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public $table = "coupons";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'code',
        'discount',
        'expire_date',
        'description',
    ];

    protected $casts = [
        'expire_date' => 'datetime',
    ];


    public function createCoupon($data)
    {
        $coupon = new Coupon();
        $coupon->title = $data['title'];
        $coupon->code = $data['code'];
        $coupon->discount = $data['discount'];
        $coupon->description = $data['description'];
        $coupon->expire_date = $data['expire_date'];
        $coupon->save();

        return $coupon;
    }


}
