<?php

namespace App\Http\Livewire;

use App\Models\User;
use LaravelViews\Views\TableView;
use LaravelViews\Facades\UI;
use LaravelViews\Facades\Header;


class UsuariosTableView extends TableView
{
    /**
     * Sets a model class to get the initial data
     */
    protected $model = User::class;
    public $searchBy = ['name', 'email'];
    protected $paginate = 20;

    protected function repository()
    {
        return User::where([['deleted', '=', 0]]);
    }

    public function headers(): array
    {
        return [
            Header::title('NOMBRE')->sortBy('name'),
            Header::title('EMAIL')->sortBy('email'),
            Header::title('NÚMERO')->sortBy('number'),
            Header::title('NÚMERO INT.')->sortBy('number_from'),
            Header::title('CREACIÓN')->sortBy('created_at'),
            ""
        ];
    }

    public function row($model)
    {
        $btn = '<button id="del" data-toggle="modal" data-target="#OpenPopUpDelete" onclick="cargarDatosDelete(' . $model->id . ', `' . $model->name . '`)" class="btn btn-sm btn-outline-danger">Eliminar</button>';
        if ($model->role == 1) {
            $btn = '<button id="del" disabled class="btn btn-sm btn-outline-danger">Eliminar</button>';
        }
        return [
            UI::editable($model, 'name'),
            UI::editable($model, 'email'),
            UI::editable($model, 'number'),
            UI::editable($model, 'number_from'),
            date_format(date_create($model->created_at), 'd/m/Y H:i:s'),
            $btn
        ];
    }

    public function update(User $model, $data)
    {
        $model->update($data);
        return;
    }
}
