<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Animal extends Model implements Authenticatable{
    use HasFactory, AuthenticatableTrait;
    public $timestamps = false; 
    protected $table = 'animal'; 
    protected $fillable = [
        'peso',
        'idade',
        'necessidade_nutricional',
        'pastagem_atual'
    ];
}
