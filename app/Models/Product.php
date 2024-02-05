<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category_id',
        'unit_mesure',
        'status_pro', 
        'quantity'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function productMouvements()
    {
        return $this->hasMany(ProductMouvement::class,'product_id','id');
    }

    public function getLastPrice()
    {
        $lastMouvement = $this->productMouvements()
            ->where('quantity', '>', 0) // Sélectionnez seulement les mouvements avec une quantité positive (entrées)
            // ->orderBy('id', 'asc') // Triez par ID décroissant
            ->first(); // Sélectionnez le premier mouvement

        if ($lastMouvement) {
            return $lastMouvement->price_un;
        }

        return $lastMouvement;
    }



}
