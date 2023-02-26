<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use App\Models\Ticket;
use LaravelViews\Facades\Header;


class TicketsTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = Ticket::class;
    public $searchBy = ['from', 'status', 'updated_at'];
    protected $paginate = 20;

    protected function repository()
    {
        return Ticket::where([['status', '!=', 'ELIMINADO']]);
    }

    public function headers(): array
    {
        return [
            Header::title('ID')->sortBy('id'),
            Header::title('DE')->sortBy('from'),
            Header::title('ÚLTIMO MENSAJE')->sortBy('messages'),
            Header::title('ESTADO')->sortBy('status'),
            Header::title('ÚLTIMA ACTUALIZACIÓN')->sortBy('updated_at'),
            ""
        ];
    }

    public function row($model)
    {
        $color = '';
        if ($model->status == 'CARGANDO') $color = '#ffd23d';
        else if ($model->status == 'ABIERTO') $color = '#47ed73';
        else if ($model->status == 'CERRADO') $color = '#b8b8b8';

        return [
            $model->id,
            $model->from()->value('name') . ' ' . $model->from()->value('surname'),
            substr($model->messages()[count($model->messages()) - 1]->message, 0, 50),
            "<div style='background-color: $color; text-align: center; border-radius: 1em; padding-top: 0.5em; padding-bottom: 0.4em;'>$model->status</div>",
            $model->updated_at,
            '<button onclick="verTicket(' . $model->id . ')" class="btn btn-sm btn-outline-primary">Ver ticket</button> <button id="del" data-toggle="modal" data-target="#OpenPopUpDelete" onclick="cargarDatosDelete(' . $model->id . ')" class="btn btn-sm btn-outline-danger">Eliminar</button>'
        ];
    }
}
