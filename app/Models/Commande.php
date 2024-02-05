<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $fillable = [
        'numero_commande',
        'fournisseur_id',
        'status_co',
        'date_commande',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'fournisseur_id');
    }

    public function details()
    {
        return $this->hasMany(DetailCommande::class, 'commande_id');
    }
}