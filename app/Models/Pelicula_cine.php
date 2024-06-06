<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula_cine extends Model
{
    protected $table = 'pelicula_cines';

    protected $fillable = ['fecha_publicacion', 'fecha_fin', 'id_pelicula', 'id_sala'];

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'id_pelicula');
    }

    public function sala()
    {
        return $this->belongsTo(Sala_cine::class, 'id_sala');
    }
}
