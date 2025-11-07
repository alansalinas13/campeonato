<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jugador;
use App\Models\DetalleFicha;
use Illuminate\Support\Facades\Storage;

//use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Barryvdh\DomPDF\Facade\Pdf;

// si usás dompdf
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Google\Cloud\Vision\V1\Image;
use Google\Cloud\Vision\V1\Feature;
use Google\Cloud\Vision\V1\AnnotateImageRequest;
use Google\Cloud\Vision\V1\BatchAnnotateImagesRequest;

class FichaJugadorController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {


        $request->validate([
            'idjugador'               => 'required|exists:jugadores,idjugador',
            'tipo_habilitacion'       => 'required|in:1,2,3',
            'tipo_traspaso'           => 'nullable|in:1,2',
            'imagen_ficha'            => 'required|image|mimes:jpeg,png,jpg|max:20480',// 20MB
            'imagen_fotocopia_cedula' => 'required|image|mimes:jpeg,png,jpg|max:20480',// 20MB
            'imagen_ficha_medica'     => 'required|image|mimes:jpeg,png,jpg|max:20480',// 20MB
            'imagen_aut_menor'        => 'nullable|image|mimes:jpeg,png,jpg|max:20480',// 20MB
        ]);

        $errores = $this->analizarFicha($request->file('imagen_ficha')->getRealPath());

        if (!empty($errores)) {
            $pdf = Pdf::loadView('reportes.errores_ficha', ['errores' => $errores]);

            return $pdf->download('reporte_errores_ficha.pdf');
        }

        $path = $request->file('imagen_ficha')->store('fichas', 'public');

        $data = $request->only(['idjugador', 'anio', 'tipo_habilitacion']);

        // Buscar ficha existente
        $ficha = DetalleFicha::where('idjugador', $data['idjugador'])
                             ->where('anio', $data['anio'])
                             ->where('tipo_habilitacion', $data['tipo_habilitacion'])
                             ->first();
        if ($ficha) {
            $data['imagen_ficha'] = 'storage/'.$path;
            if ($ficha && $ficha->imagen_ficha && Storage::disk('public')->exists(str_replace('storage/', '', $ficha->imagen_ficha))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $ficha->imagen_ficha));
            }
            $ficha->update($data);
        }
        else {
            // Guardar detalle de ficha
            DetalleFicha::create([
                'idjugador'         => $request->idjugador,
                'anio'              => $request->anio,
                'tipo_habilitacion' => $request->tipo_habilitacion,
                'imagen_ficha'      => 'storage/'.$path,
            ]);
        }

        // Actualizar jugador
        $jugador = Jugador::findOrFail($request->idjugador);
        $jugador->jugest = (int)$request->tipo_habilitacion;
        $jugador->jugfechab = now();
        $jugador->save();

        return redirect()->route('jugadores.porClub', $jugador->idclub)->with('success', 'Jugador habilitado en '.strtoupper($request->tipo_habilitacion));
    }

    public function analizarFicha($rutaAbsoluta) {
        $errores = [];
        try {
            //$vision = new ImageAnnotatorClient();
            $vision = new ImageAnnotatorClient();

            $image = (new Image())->setContent(file_get_contents($rutaAbsoluta));
            $feature = (new Feature())->setType(Feature\Type::TEXT_DETECTION);

            $annotateRequest = (new AnnotateImageRequest())
                ->setImage($image)
                ->setFeatures([$feature]);

            $batchRequest = new BatchAnnotateImagesRequest();
            $batchRequest->setRequests([$annotateRequest]);

            $response = $vision->batchAnnotateImages($batchRequest);

            $responses = $response->getResponses();

            foreach ($responses as $res) {
                if ($res->getError()->getMessage()) {
                    $errores[] = "Error de API: ".$res->getError()->getMessage();
                }
                else {
                    $annotations = $res->getTextAnnotations();
                    if (count($annotations) === 0) {
                        $errores[] = "No se detectó texto en la imagen.";
                    }
                    else {
                        $contenido = strtolower($annotations[0]->getDescription());

                        if (!str_contains($contenido, 'liga') && !str_contains($contenido, 'ufi')) {
                            $errores[] = "No se encontró el texto 'Liga' ni 'UFI' en la ficha.";
                        }
                    }
                }
            }

            $vision->close();
        }
        catch (\Exception $e) {
            $errores[] = "Excepción detectada al usar Google Vision API: ".$e->getMessage();
        }

        return $errores;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
