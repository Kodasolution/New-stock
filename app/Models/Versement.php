<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Versement extends Model
{
    use HasFactory;
    protected $fillable = [
        'salaire_id',
        'montant_verse',
        'date_verse',
        'status',
        'mois',
        'has_dette',
        'dette_montant'  
    ];


    public function salaire()
    {
        return $this->belongsTo(Salaire::class);
    }
}
