<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageDetail extends Model
{
    use HasFactory;

    public $table = "package_details";
    public $timestamps = true;
    protected $primaryKey = 'id';
   
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
