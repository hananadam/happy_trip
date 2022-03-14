<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{

    public $table = "passengers";
    public $timestamps = true;
    protected $primaryKey = 'id';
   
}
