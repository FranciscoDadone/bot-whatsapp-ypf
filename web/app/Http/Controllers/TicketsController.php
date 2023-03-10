<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\WpNotification;
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

        $users = User::where([['id', '!=', auth()->user()->id], ['deleted', 0]])->get();

        $assigned_to = ($ticket->assigned_to) ? User::where([['id', $ticket->assigned_to]])->first() : '';

        return view('ticket', compact('ticket', 'color_ticket', 'users', 'assigned_to'));
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

        $app_url = getEnv('APP_URL');
        $user = User::where([['id', $user_id]]);

        if ($user->exists()) {
            WpNotification::create([
                'message' => "🚩 Fuiste asignado el ticket #$ticket_id \nIngresá a $app_url/ticket/ver/$ticket_id",
                'phone_number' => $user->value('number_from')
            ]);
        }

        return back();
    }
}
