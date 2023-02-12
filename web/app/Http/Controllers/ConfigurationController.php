<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneNumber;

class ConfigurationController extends Controller
{
    public function numeros() {
        return view('configuracion.numeros');
    }

    public function delete_number(Request $request) {
        PhoneNumber::where([['id', $request->id]])->update([
            'eliminado' => 1
        ]);
        return "Ok";
    }

    public function new_number(Request $request) {
        PhoneNumber::create([
            'name' => $request->nombre,
            'surname' => $request->apellido,
            'station' => $request->estacion,
            'description' => $request->descripcion,
            'number' => $request->numero
        ]);
        return redirect()->back();
    }
}
