<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function delete(Request $request) {
        Ticket::where([['id', $request->id]])->update([
            'status' => 'ELIMINADO'
        ]);
        return "Ok";
    }
}
