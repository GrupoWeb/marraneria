<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\error_lugar;
use Illuminate\Support\Facades\DB;

class consumo extends Controller
{
    public function setError(Request $request){

        $fecha = new \DateTime();
        try {
            DB::beginTransaction();

            $error = new error_lugar;

            $error->id_edificio = (int)$request->edificio;
            $error->id_error = (int)$request->error;
            $error->fecha_registro = $fecha->format('Y-m-d H:i:s');
            $error->save();

            DB::commit();

            return response()->json($error,200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th,200);
        }
    }
}
