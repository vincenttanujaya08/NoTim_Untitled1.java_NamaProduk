<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commodity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'unit', 'description'];

    public function stocks()
    {
        return $this->hasMany(CommodityStock::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
