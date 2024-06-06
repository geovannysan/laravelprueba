<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sala_cine extends Model
{
    protected $primaryKey = 'id_sala';

    protected $fillable = ['nombre', 'estado'];

    public function peliculasCine()
    {
        return $this->hasMany(Pelicula_cine::class,'id_sala');
    }
}
