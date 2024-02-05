<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'montant',
        'date_credit',
        'description',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
