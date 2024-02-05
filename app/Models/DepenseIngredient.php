<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepenseIngredient extends Model
{
    use HasFactory;
    protected $table = 'depense_ingredients';
    protected $fillable = [
        'depense_id',
        'ingredient_id',
        'prix_unitaire',
        'date_creation',
    ];

    public function depense()
    {
        return $this->belongsTo(Expenses::class, 'depense_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }

    
}
