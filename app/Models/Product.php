<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'stock_quantity'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price_at_purchase');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    //relacion polimorfica: permite tener varias imagenes para un producto en este caso
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
    }
    
}

