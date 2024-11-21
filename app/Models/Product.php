<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, Laravel assumes 'products' as the default table)
    protected $table = 'products';

    // Define the fillable fields to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'name',
        'category_id',
        'rating',
        'image',
        'price',
    ];

    // Define the relationship between Product and Category (Many-to-One)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings()
    {
        return $this->belongsTo(Rating::class);
    }
}