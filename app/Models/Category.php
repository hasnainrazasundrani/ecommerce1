<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional, Laravel assumes 'categories' as the default table)
    protected $table = 'categories';

    // Define the fillable fields to protect against mass-assignment vulnerabilities
    protected $fillable = [
        'name',
    ];

    // Define the relationship between Category and Product (One-to-Many)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
