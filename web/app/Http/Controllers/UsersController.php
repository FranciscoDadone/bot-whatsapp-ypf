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
            'password' => Hash::make($request->password)
        ]);
        return redirect()->back();
    }
}
