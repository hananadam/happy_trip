<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;


    public $table = "ads";
    public $timestamps = true;
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'image',
    ];

    protected $append = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        return;
    }
}
