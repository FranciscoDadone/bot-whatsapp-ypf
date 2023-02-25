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
        return [
            $model->id,
            $model->from()->value('name') . ' ' . $model->from()->value('surname'),
            substr($model->messages()[count($model->messages()) - 1]->message, 0, 50),
            $model->status,
            $model->updated_at,
            '<button onclick="verTicket(' . $model->id . ')" class="btn btn-sm btn-outline-primary">Ver ticket</button> <button onclick="deleteTicket(' . $model->id . ')" class="btn btn-sm btn-outline-danger">Eliminar</button>'
        ];
    }
}
