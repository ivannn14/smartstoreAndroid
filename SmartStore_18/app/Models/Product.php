<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'status',
        'category',
        'sku',  // Add this line
        'image',
    ];

    public static function validateBarcode($barcode)
    {
        // Remove any whitespace
        $barcode = trim($barcode);
        
        // Check if it's a valid EAN-13 or UPC-A format
        if (!preg_match('/^[0-9]{12,13}$/', $barcode)) {
            return false;
        }
        
        return true;
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stockAlert()
    {
        return $this->hasOne(StockAlert::class);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '<=', 10);
    }
}
