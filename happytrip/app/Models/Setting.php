<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $table = "settings";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'phone',
        'address',
        'logo',
        'email',
    ];

}
