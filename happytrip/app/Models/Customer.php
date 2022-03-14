<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    public $table = "customers";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable =
        ['name',
            'email',
            'dailing_code',
            'title',
            'gender',
            'address',
            'country',
            'mobile_num',
            'nationality',
            'id_number',
            'id_expiry_date',
            'currency',
            'language',
            'num_adults',
            'num_children',
        ];

    //  protected $casts = [
    //     'id_expiry_date' => 'date:Y-m-d',
    // ];

    public function user()
    {
        return $this->hasOne("User");
    }

    public function cards()
    {
        return $this->hasMany(CustomerCard::class);
    }

    public function children()
    {
        return $this->hasMany(CustomerChildren::class);
    }

    public function reviews()
    {
        return $this->hasMany(Rating::class);
    }

    
    public function createRate($hotelCode, $rate, $comment)
    {
        $Rating = new Rating();
        $Rating->customer_id = $this->id;
        $Rating->code = $hotelCode;
        $Rating->rate = $rate;
        $Rating->comment = $comment;
        $Rating->save();

        return $Rating;
    }

    public function addChildAge($age)
    {
        $child = new CustomerChildren();
        $child->customer_id = $this->id;
        $child->age = $age;
        $child->created_at = Carbon::today();
        $child->save();
    }

    public function createCard($data)
    {

        $card = new CustomerCard();
        $card->customer_id = $this->id;
        $card->name = $data['name'];
        $card->number = $data['number'];
        $card->expire_month = $data['expire_month'];
        $card->expire_year = $data['expire_year'];
        $card->cvv = $data['cvv'];
        $card->address = $data['address'];
        if ($data['confirmed'] == 'true') {
            $card->confirmed = 1;
        } else {
            $card->confirmed = 0;
        }
        $card->save();

        return $card;
    }


    public function saveBooking($type,$provider,$reference_code, $transaction_date, $check_in, $check_out,$code,$hotel,$room_code,$room_price,$voucher,$loyality,$nights,$subtotal,$vat,$total,$remark,$fly_from,$fly_to,$bags_num,$bags_weight,$payment_method,$card_id)
    {
        $book = new Booking();
        $book->customer_id = $this->id;
        $book->type = $type;
        $book->provider = $provider;
        $book->reference_code = $reference_code;
        $book->transaction_date = $transaction_date;
        $book->check_in = $check_in;
        $book->check_out = $check_out;
        $book->code = $code;
        $book->hotel = $hotel;
        $book->room_code = $room_code;
        $book->room_price = $room_price;
        $book->voucher = $voucher;
        $book->loyality = $loyality;
        $book->nights = $nights;
        $book->subtotal = $subtotal;
        $book->vat = $vat;
        $book->total = $total;
        $book->remark = $remark;
        $book->fly_from = $fly_from;
        $book->fly_to = $fly_to;
        $book->bags_num = $bags_num;
        $book->bags_weight = $bags_weight;
        $book->payment_method = $payment_method;
        $book->card_id = $card_id;
      
        $book->save();

        return $book;
    }

}
