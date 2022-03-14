<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public $table = "bookings";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'reference_code',
        'transaction_date',
        'check_in',
        'check_out',
        'code',
        'hotel',
        'transaction_date',
        'transaction_date',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotelbeds\Hotel::class, 'code');
    }
}
