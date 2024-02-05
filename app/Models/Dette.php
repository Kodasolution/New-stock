<?php

namespace App\Models;

use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dette extends Model
{
    use HasFactory;
    protected $table = 'dettes';
    protected $fillable = [
        'type',
        'user_id',
        'client_id',
        'montant_total',
        'status',
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id','id');
    }

    public function remboursements()
    {
        return $this->hasMany(RemboursementDette::class, 'dette_id');
    }

    public function details()
    {
        return $this->hasMany(DetteDetail::class, 'dette_id');
    }

}