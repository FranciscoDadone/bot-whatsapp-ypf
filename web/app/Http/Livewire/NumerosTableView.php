<?php

namespace App\Http\Livewire;

use LaravelViews\Views\TableView;
use App\Models\Numeros;
use LaravelViews\Facades\UI;
use LaravelViews\Facades\Header;


class NumerosTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = Numeros::class;
    public $searchBy = ['estacion', 'nombre', 'apellido', 'numero'];
    protected $paginate = 20;

    protected function repository()
    {
        return Numeros::where([['eliminado', '!=', 1]]);
    }

    public function headers(): array
    {
        return [
            Header::title('NOMBRE')->sortBy('nombre'),
            Header::title('APELLIDO')->sortBy('apellido'),
            Header::title('ESTACIÓN')->sortBy('estacion'),
            'DESCRIPCIÓN',
            'NÚMERO',
            ""
        ];
    }

    public function row($model)
    {
        return [
            UI::editable($model, 'nombre'),
            UI::editable($model, 'apellido'),
            UI::editable($model, 'estacion'),
            UI::editable($model, 'descripcion'),
            UI::editable($model, 'numero'),
            "<button onclick='deleteNum({$model->id})'>Eliminar</button>"
        ];
    }

    public function update(Numeros $model, $data)
    {
        $model->update($data);
        return;
    }
}
