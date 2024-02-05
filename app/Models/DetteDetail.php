<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetteDetail extends Model
{
    use HasFactory;
    protected $table = 'details_dette';
    protected $fillable = [
        'dette_id',
        'montant',
        'motif',
        'date_creation',
    ];

    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }
}
