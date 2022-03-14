<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $table = "cities";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'image',
    ];


    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destinationCode', 'code');
    }
}
