<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemboursementDette extends Model
{
    use HasFactory;
    protected $table = 'remboursements_dettes';
    protected $fillable = [
        'dette_id',
        'montant_rembourse',
        'date_rembourse',
    ];

    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }
}
