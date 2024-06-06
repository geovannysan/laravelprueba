<?php

namespace App\Http\Controllers;

use App\Models\Pelicula as ModelsPelicula;
use App\Models\Pelicula_cine;
use App\Models\Sala_cine;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

/**
 * @OA\Info(title="API Usuarios", version="1.0")
 *
 * @OA\Server(url="http://swagger.local")
 */
class Pelicula extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/api/peliculas",
     *     summary="Mostrar peliculas por nombre y id de sala o fecha de publicacion",
     *     @OA\Parameter(
     *         name="nombre",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         description="Nombre de la pelicula"
     *     ),
     *     @OA\Parameter(
     *         name="id_sala",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="ID de la sala"
     *     ),
     *     @OA\Parameter(
     *         name="fecha_publicacion",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date"),
     *         description="Fecha de publicación de la pelicula"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las películas filtradas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Pelicula")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No se encontraron películas"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    public function index(Request $request)
    {
        if ($request->has('nombre') && $request->has('id_sala')) {
            return $this->buscarPorNombreYSala($request);
        }

        if ($request->has('fecha_publicacion')) {
            return $this->buscarPorFechaPublicacion($request);
        }

        return response()->json(ModelsPelicula::all());
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
                'duracion' => 'required|integer',
            ]);

            $pelicula = ModelsPelicula::create($request->all());
            return response()->json($pelicula, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la película.'], 500);
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
        try {
            // Busca la película por su ID
            $pelicula = ModelsPelicula::findOrFail($id);

            return response()->json($pelicula);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Película no encontrada.'], 404);
        }
    }
    private function buscarPorNombreYSala(Request $request)
    {
        $nombre = $request->input('nombre');
        $idSala = $request->input('id_sala');
        $peliculas = ModelsPelicula::where('nombre', 'like', '%' . $nombre . '%')
            ->whereHas('salaCines', function ($query) use ($idSala) {
                $query->where('id_sala', $idSala);
            })
            ->with(['salaCines.sala']) // Cargar la relación salaCines y la relación sala de cada salaCine
            ->get();

        return response()->json($peliculas);
    }


    private function buscarPorFechaPublicacion(Request $request)
    {
        $fechaPublicacion = $request->input('fecha_publicacion');
        $peliculas = ModelsPelicula::whereHas('salaCines', function ($query) use ($fechaPublicacion) {
            $query->where('fecha_publicacion', $fechaPublicacion);
        })
            ->with('salaCines')
            ->get();

        $cantidad = count($peliculas);

        return response()->json([
            'cantidad' => $cantidad,
            'peliculas' => $peliculas,
        ]);
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
            $pelicula = ModelsPelicula::findOrFail($id);

            $request->validate([
                'nombre' => 'required|string',
                'duracion' => 'required|integer',
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

            $pelicula = ModelsPelicula::findOrFail($id);

            if ($pelicula->salaCines()->exists()) {
                return response()->json(['error' => 'No se puede eliminar la película porque tiene registros asociados en pelicula_cines.'], 400);
            }
            $pelicula->delete();
            return response()->json(['message' => 'Película eliminada correctamente.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar la película.'], 500);
        }
    }
}
