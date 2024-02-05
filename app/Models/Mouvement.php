<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'referenceMov',
        'typeMouv',
        'date_flux',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productMouvements()
    {
        return $this->hasMany(ProductMouvement::class);
    }

}
