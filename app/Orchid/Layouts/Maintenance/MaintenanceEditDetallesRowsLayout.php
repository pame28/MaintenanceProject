<?php

namespace App\Orchid\Layouts\Maintenance;

use App\Models\Maintenance_type;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class MaintenanceEditDetallesRowsLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Relation::make('maintenance_type_id')
                ->title('Tipo de mantenimiento')
                ->fromModel(Maintenance_type::class, 'type')
                ->value(old('maintenance_type_id', $this->query['maintenance']->maintenance_type_id))
                ->disabled($this->query->has('userOwner') && Auth::user()->hasAccess('platform.maintenance.editOnly') ? true : false)
                ->help('Tipo de mantenimiento'),

            TextArea::make('description')
                ->title('Descripción del problema')
                ->rows(4)
                ->maxlength(200)
                ->value(old('description', $this->query['maintenance']->description))
                ->placeholder('Descripción del mantenimiento')
                ->readonly($this->query->has('userOwner') && Auth::user()->hasAccess('platform.maintenance.editOnly') ? true : false),

            TextArea::make('solution')
                ->title('Solución')
                ->rows(4)
                ->maxlength(200)
                ->value(old('solution', $this->query['maintenance']->solution))
                ->placeholder('Solución del mantenimiento')
                ->readonly($this->query->has('userOwner') && Auth::user()->hasAccess('platform.maintenance.editOnly') ? true : false),

            Select::make('status')
                ->title('Estado')
                ->options([
                    'Pendiente' => 'Pendiente',
                    'En proceso' => 'En proceso',
                    'Finalizado' => 'Finalizado',
                ])
                ->required()
                ->value(old('status', $this->query['maintenance']->status))
                ->popover('Pendiente: El mantenimiento no ha sido realizado. En proceso: El mantenimiento se encuentra en proceso. Finalizado: El mantenimiento ha sido realizado.')
                ->help('Estado del mantenimiento'),

        ];
    }
}
