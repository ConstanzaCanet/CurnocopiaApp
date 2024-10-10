<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_price', 'status', 'shipping_address','uuid','preference','api_response', 'zip','cae','cae_vto'];
    protected $cast = [
        'api_response' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
                    ->withPivot('quantity', 'price_at_purchase');
    }

    public function scopeResponse($query)
    {
        return $query->whereNotNull('api_respose');
    }

    public function getTotal(){
        return $this->ordelItem->reduce(function ($cart, $item){
            return $cart + $item->price;
        }, 0);
    }

    //UUID
    protected static function booted(): void
    {
        static::creating(function(Order $order){
            $order->uuid = str()->uuid();
        });
    }
}

