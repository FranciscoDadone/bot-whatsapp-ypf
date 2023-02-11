<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Numeros;

class ConfigurationController extends Controller
{
    public function numeros() {
        return view('configuracion.numeros');
    }

    public function delete_number(Request $request) {
        Numeros::where([['id', $request->id]])->delete();
        return "Ok";
    }

    public function new_number(Request $request) {
        Numeros::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'estacion' => $request->estacion,
            'descripcion' => $request->descripcion,
            'numero' => $request->numero
        ]);
        return redirect()->back();
    }
}
