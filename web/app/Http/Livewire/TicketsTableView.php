<?php

namespace App\Http\Livewire;

use App\Filters\StatusFilter;
use LaravelViews\Views\TableView;
use App\Models\Ticket;
use LaravelViews\Facades\Header;
use Okipa\LaravelTable\Filters\ValueFilter;
use Okipa\LaravelTable\Livewire\Table;


class TicketsTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = Ticket::class;
    public $searchBy = ['from', 'status', 'updated_at'];
    protected $paginate = 20;
    public $placeholder = 'Buscar';

    protected function repository()
    {
        return Ticket::where([['status', '!=', 'ELIMINADO']])->orderBy('id', 'desc');
    }

    public function headers(): array
    {
        return [
            Header::title('ID')->sortBy('id'),
            Header::title('DE')->sortBy('from'),
            Header::title('ÚLTIMO MENSAJE'),
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
        else if ($model->status == 'EN_PROCESO') $color = '#fcf453';
        else if ($model->status == 'CERRADO') $color = '#b8b8b8';

        $last_message = $model->messages()[count($model->messages()) - 1]->message;

        if (str_contains($last_message, 'media/')) {
            if (str_contains($last_message, '.png')) $media = '( IMAGEN )';
            else if (str_contains($last_message, '.mp4')) $media = '( VIDEO )';
        }

        $status = $model->status;
        if ($model->status == 'EN_PROCESO') $status = 'EN PROCESO';

        return [
            $model->id,
            $model->from()->value('name') . ' ' . $model->from()->value('surname'),
            $media ?? substr($last_message, 0, 50),
            "<div style='background-color: $color; text-align: center; border-radius: 1em; padding-top: 0.5em; padding-bottom: 0.4em;'>$status</div>",
            date_format(date_create($model->updated_at), 'd/m/Y H:i:s'),
            '<button onclick="verTicket(' . $model->id . ')" class="btn btn-sm btn-outline-primary">Ver ticket</button> <button id="del" data-toggle="modal" data-target="#OpenPopUpDelete" onclick="cargarDatosDelete(' . $model->id . ')" class="btn btn-sm btn-outline-danger">Eliminar</button>'
        ];
    }

    protected function filters()
{
    return [
        new StatusFilter
    ];
}
}
