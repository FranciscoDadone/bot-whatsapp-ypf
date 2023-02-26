<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use App\Models\PhoneNumber;
use LaravelViews\Facades\UI;
use LaravelViews\Facades\Header;


class NumerosTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = PhoneNumber::class;
    public $searchBy = ['station', 'name', 'surname', 'number'];
    protected $paginate = 20;

    protected function repository()
    {
        return PhoneNumber::where([['deleted', '!=', 1]]);
    }

    public function headers(): array
    {
        return [
            Header::title('NOMBRE')->sortBy('name'),
            Header::title('APELLIDO')->sortBy('surname'),
            Header::title('ESTACIÓN')->sortBy('station'),
            'DESCRIPCIÓN',
            'NÚMERO',
            'NÚMERO INT.',
            ""
        ];
    }

    public function row($model)
    {
        return [
            UI::editable($model, 'name'),
            UI::editable($model, 'surname'),
            UI::editable($model, 'station'),
            UI::editable($model, 'description'),
            UI::editable($model, 'number'),
            UI::editable($model, 'number_from'),
            '<button id="del" data-toggle="modal" data-target="#OpenPopUpDelete" onclick="cargarDatosDelete(' . $model->id . ', ' . $model->number . ')" class="btn btn-sm btn-outline-danger">Eliminar</button>'
        ];
    }

    public function update(PhoneNumber $model, $data)
    {
        $model->update($data);
        return;
    }
}
