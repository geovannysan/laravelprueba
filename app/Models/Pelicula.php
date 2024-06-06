<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Pelicula",
 *     type="object",
 *     title="Pelicula",
 *     description="Modelo de Pelicula",
 *     @OA\Property(
 *         property="id_pelicula",
 *         type="integer",
 *         description="ID de la película"
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre de la película"
 *     ),
 *     @OA\Property(
 *         property="duracion",
 *         type="integer",
 *         description="Duración de la película en minutos"
 *     )
 * )
 */
class Pelicula extends Model
{
    protected $primaryKey = 'id_pelicula';

    protected $fillable = ['nombre', 'duracion'];
    public function salaCines()
    {
        return $this->hasMany(Pelicula_cine::class, 'id_pelicula'); // Asegúrate de especificar la clave foránea correctamente
    }

    use HasFactory;
}
