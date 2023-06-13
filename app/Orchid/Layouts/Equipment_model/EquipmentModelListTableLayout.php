<?php

namespace App\Orchid\Layouts\Equipment_model;

use App\Models\Equipment_model;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class EquipmentModelListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'models';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'No.')
                ->sort()
                ->render(function (Equipment_model $model) {
                    return Link::make($model->id)
                        ->route('platform.model.card', $model);
                })
                ->filter(Input::make()),
            TD::make('brand_name', 'Marca')
                ->sort()
                ->filter(Input::make()),
            TD::make('model', 'Modelo')
                ->sort()
                ->filter(Input::make()),
            TD::make('acciones', 'Acciones')
                ->render(fn (Equipment_model $model) => DropDown::make()
                ->align(TD::ALIGN_CENTER)
                ->icon('options-vertical')
                ->list([
                    Link::make('Ver')
                        ->icon('eye')
                        ->route('platform.model.card', $model),
                    Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.model.edit', 'systems.admin']))
                            ->route('platform.model.edit', $model),
                    Button::make('Eliminar')
                        ->icon('trash')
                        ->method('delete')
                        ->parameters(['model' => $model->id])
                        ->canSee(Auth::user()->hasAnyAccess(['platform.model.delete', 'systems.admin']))
                        ->confirm('¿Estás seguro de querer eliminar este modelo?'),
                ])),
        ];
    }
}
