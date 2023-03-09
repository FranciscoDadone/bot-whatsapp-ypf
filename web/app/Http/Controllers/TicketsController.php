<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function delete(Request $request) {
        Ticket::where([['id', $request->id]])->update([
            'status' => 'ELIMINADO'
        ]);
        return "Ok";
    }

    public function view_ticket($id) {
        $ticket = Ticket::where('id', $id)->first();
        $color_ticket = '';
        if ($ticket->status == 'CARGANDO') $color_ticket = '#ffd23d';
        else if ($ticket->status == 'ABIERTO') $color_ticket = '#47ed73';
        else if ($ticket->status == 'EN_PROCESO') $color_ticket = '#fcf453';
        else if ($ticket->status == 'CERRADO') $color_ticket = '#b8b8b8';

        $users = User::where([['id', '!=', auth()->user()->id]])->get();

        return view('ticket', compact('ticket', 'color_ticket', 'users'));
    }

    public function change_status($id, $status) {
        Ticket::where('id', $id)->update([
            'status' => $status
        ]);
    }

    public function assign_ticket(Request $request) {
        $ticket_id = $request->ticket_id;
        $user_id = $request->user;

        Ticket::where([['id', $ticket_id]])->update([
            'assigned_to' => $user_id
        ]);

        return back();
    }
}
