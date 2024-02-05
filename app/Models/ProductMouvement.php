<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMouvement extends Model
{
    use HasFactory;
    protected $fillable = [
        'mouvement_id',
        'product_id',
        'price_un',
        'quantity',
        'price_tot',
        'date_flux',
    ];

    public function mouvement()
    {
        return $this->belongsTo(Mouvement::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
