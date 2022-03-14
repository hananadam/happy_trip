<?php

namespace App\Models\Kiwi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    const LINK_IMAGE = 'https://images.kiwi.com/airlines';

    public function getImage($code)
    {
        return self::LINK_IMAGE.'/64/'.$code.'.png';
    }
}
