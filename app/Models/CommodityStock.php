<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityStock extends Model
{
    use HasFactory;

    protected $fillable = ['commodity_id', 'grade', 'quantity'];

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }
}
