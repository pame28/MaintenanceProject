<?php

namespace App\Orchid\Layouts\Maintenance_type;

use App\Models\Maintenance_type;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MaintenanceTypeListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'types';

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
                ->render(function (Maintenance_type $maintenance_type) {
                    return Link::make($maintenance_type->id)
                        ->route('platform.maintenance_type.card', $maintenance_type);
                })
                ->filter(Input::make()),
            TD::make('type', 'Tipo de mantenimiento')
                ->sort()
                ->filter(Input::make()),
            TD::make('description', 'Descripción')
                ->sort()
                ->render(
                    function (Maintenance_type $maintenance_type) {
                        return substr($maintenance_type->description, 0, 50) . '...';
                    }
                )
                ->filter(Input::make()),
            TD::make('acciones', 'Acciones')
                ->render(fn (Maintenance_type $type)=> DropDown::make()
                    ->aling(TD::ALIGN_CENTER)
                    ->icon('options-vertical')
                    ->list([
                        Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.maintenance_type.card', $type),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.maintenance_type.edit', 'systems.admin']))
                            ->route('platform.maintenance_type.edit', $type),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['type' => $type->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.maintenance_type.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar este tipo de mantenimiento?'),
                    ])),
        ];
    }
}
