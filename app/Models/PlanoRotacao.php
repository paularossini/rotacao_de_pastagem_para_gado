<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class PlanoRotacao extends Model implements Authenticatable{
    use HasFactory, AuthenticatableTrait;
    public $timestamps = false; 
    protected $table = 'plano_rotacao'; 
    protected $fillable = [
        'dia',
        'pastagem_id',
        'animais',
        'qtd_animal',
        'forragem_disponivel'
    ];
}