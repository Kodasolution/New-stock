<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salaire extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'montant',
        'title',
        'date_in',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}