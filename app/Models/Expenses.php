<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;
    protected $table = 'depenses';
    protected $fillable = [
        'user_id',
        'title',
        'amount',
        'date_creation',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'depense_ingredients', 'depense_id', 'ingredient_id');
    }
    
    public function details()
    {
        return $this->hasMany(DepenseIngredient::class, 'depense_id');
    }
}