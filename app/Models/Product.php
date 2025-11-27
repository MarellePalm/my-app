<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'sku',
        'stock_quantity'
    ];

    protected $appends = [
    'created_at_formatted',
    'updated_at_formatted',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

     protected function createdAtFormatted(): Attribute
    {
       return Attribute::make(
            get: fn() => $this->created_at?->diffForHumans()
        );
    }

    protected function updatedAtFormatted(): Attribute
    {
       return Attribute::make(
            get: fn() => $this->updated_at?->diffForHumans()
        );
    }
}
