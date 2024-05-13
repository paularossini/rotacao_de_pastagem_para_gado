<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class Pastagem extends Model implements Authenticatable{
    use HasFactory, AuthenticatableTrait;
    public $timestamps = false; 
    protected $table = 'pastagem'; 
    protected $fillable = [
        'capacidade_suporte',
        'quantidade_forragem',
        'dias_recuperacao',
        'forragem_disponivel'
    ];
    
}