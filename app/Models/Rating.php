<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'product_id',
    ];

    /**
     * Get the user who made the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product the rating is for.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
