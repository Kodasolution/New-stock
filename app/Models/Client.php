<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'firstName',
        'lastName',
        'phone',
        'email',
        'adress',
    ];

    public function creditClients()
    {
        return $this->hasMany(CreditClient::class);
    }
}
