<?php

namespace App\Http\Controllers;

use App\Models\Sala_cine as ModelsSala_cine;
use Illuminate\Http\Request;

class Sala_cine extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->buscarPorNombreSala($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string',
                'estado' => 'required|integer',
            ]);

            $pelicula = ModelsSala_cine::create($request->all());
            return response()->json($pelicula, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la Sala.'], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function buscarPorNombreSala(Request $request)
    {
        $nombreSala = $request->input('nombre');
        $sala = ModelsSala_cine::where('nombre', $nombreSala)->first();

        if (!$sala) {
            return response()->json(['message' => 'Sala de cine no encontrada'], 404);
        }

        $cantidadPeliculas = $sala->peliculasCine()->count();

        if ($cantidadPeliculas < 3) {
            $mensaje = 'Sala casi Vacía';
        } elseif ($cantidadPeliculas >= 3 && $cantidadPeliculas <= 5) {
            $mensaje = 'Sala casi Llena';
        } else {
            $mensaje = 'Sala Llena';
        }

        return response()->json(['mensaje' => $mensaje, 'cantidad' => $cantidadPeliculas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $pelicula = ModelsSala_cine::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string',
                'estado' => 'required|integer',
            ]);

            $pelicula->update($request->all());
            return response()->json($pelicula, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            
            $pelicula = ModelsSala_cine::findOrFail($id);

            if ($pelicula->peliculasCine()->exists()) {
                return response()->json(['error' => 'No se puede eliminar la sala porque tiene registros asociados en pelicula_cines.'], 400);
            }
            $pelicula->delete();
            return response()->json(['message' => 'sala eliminada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la sala.'], 500);
        }
    }
}
