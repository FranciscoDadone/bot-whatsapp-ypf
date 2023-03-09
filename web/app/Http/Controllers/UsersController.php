<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PhoneNumber;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function usuarios() {
        return view('usuarios');
    }

    public function delete_number(Request $request) {
        PhoneNumber::where([['id', $request->id]])->update([
            'deleted' => 1
        ]);
        return "Ok";
    }

    public function new(Request $request) {
        User::create([
            'name' => $request->nombre . ' ' . $request->apellido,
            'role' => 2,
            'email' => $request->email,
            'number' => $request->numero,
            'password' => Hash::make($request->password)
        ]);
        return redirect()->back()->with('success', "Usuario agregado con éxito! Ahora que $request->numero envíe un WhatsApp con 'verificar' para completar la verificación.");
    }

    public function delete($id) {
        User::where([['id', $id]])->update([
            'deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Usuario eliminado con éxito.');
    }
}
