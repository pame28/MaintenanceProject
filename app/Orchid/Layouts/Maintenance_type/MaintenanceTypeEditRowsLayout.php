<?php

namespace App\Orchid\Layouts\Maintenance_type;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class MaintenanceTypeEditRowsLayout extends Rows
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
            Input::make('type')
                ->title('Tipo')
                ->placeholder('Tipo de mantenimiento')
                ->value(old('type', $this->query['maintenance_type']->type))
                ->help('Máximo 100 caracteres'),

            TextArea::make('description')
                ->title('Descripción')
                ->rows(4)
                ->maxlength(200)
                ->value(old('description', $this->query['maintenance_type']->description))
                ->placeholder('Descripción del tipo de mantenimiento'),

            Group::make([
                Button::make('Guardar')
                    ->icon('check')
                    ->type(Color::SUCCESS())
                    ->method('createOrUpdate')
                    ->canSee(!$this->query['maintenance_type']->exists)
                    ->confirm('¿Estás seguro de querer guardar este tipo de mantenimiento?'),

                Button::make('Actualizar')
                    ->icon('check')
                    ->type(Color::PRIMARY())
                    ->method('createOrUpdate')
                    ->canSee($this->query['maintenance_type']->exists)
                    ->confirm('¿Estás seguro de querer actualizar este tipo de mantenimiento?'),

                Link::make('Cancelar')
                    ->icon('close')
                    ->route('platform.maintenance_type.list')
                    ->type(Color::DEFAULT()),
            ])->autoWidth(),
        ];
    }
}
