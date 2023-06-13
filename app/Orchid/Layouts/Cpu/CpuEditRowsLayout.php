<?php

namespace App\Orchid\Layouts\Cpu;

use App\Models\Equipment_model;
use App\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class CpuEditRowsLayout extends Rows
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
            Input::make('inventory_number')
                ->title('Número de inventario')
                ->placeholder('IF-001-A')
                ->value(old('inventory_number', $this->query['cpu']->inventory_number))
                ->popover('Número de inventario con el que se identifica el CPU, este es proporcionado por el departamento de secretaría.'),

            Input::make('serial_number')
                ->title('Número de serie')
                ->placeholder('ABC-123-XYZ')
                ->value(old('serial_number', $this->query['cpu']->serial_number))
                ->popover('El número de serie es un código único que trae el CPU de fábrica, revisa la etiqueta del CPU para obtenerlo.'),

            Relation::make('model_id')
                ->title('Modelo')
                ->fromModel(Equipment_model::class, 'model')
                ->value(old('model_id', $this->query['cpu']->model_id))
                ->placeholder('Seleccione el modelo del CPU'),

            Input::make('storage_capacity')
                ->title('Capacidad de almacenamiento')
                ->popover('No olvides especificar la unidad de medida, por ejemplo: 500 GB, 2 TB.')
                ->placeholder('500 GB')
                ->value(old('storage_capacity', $this->query['cpu']->storage_capacity)),

            Input::make('ram')
                ->title('Memoria RAM')
                ->placeholder('4 GB')
                ->popover('No olvides especificar la unidad de medida, por ejemplo: 4 GB.')
                ->value(old('ram', $this->query['cpu']->ram)),

            DateTimer::make('date_of_purchase')
                ->title('Fecha de compra')
                ->placeholder('Fecha de compra del CPU')
                ->value(old('date_of_purchase', $this->query['cpu']->date_of_purchase))
                ->format('Y-m-d'),

            Select::make('cpu_status')
                ->title('Estado')
                ->options([
                    'Disponible' => 'Disponible',
                    'Asignado' => 'Asignado',
                    'En mantenimiento' => 'En mantenimiento',
                    'Obsoleto' => 'Obsoleto',
                ])
                ->value(old('cpu_status', $this->query['cpu']->cpu_status))
                ->popover('El estado del CPU puede ser: Disponible, En uso, En reparación, De baja.'),

            TextArea::make('observations')
                ->title('Observaciones')
                ->placeholder('Observaciones del Cpu')
                ->rows(10)
                ->value(old('observations', $this->query['cpu']->observations))
                ->popover('Observaciones del CPU'),

            Group::make([
                Button::make('Guardar')
                    ->icon('check')
                    ->type(Color::SUCCESS())
                    ->method('createOrUpdate')
                    ->canSee(!$this->query['cpu']->exists)
                    ->confirm('¿Estás seguro de querer guardar este CPU?'),

                Button::make('Actualizar')
                    ->icon('check')
                    ->type(Color::PRIMARY())
                    ->method('createOrUpdate')
                    ->canSee($this->query['cpu']->exists)
                    ->confirm('¿Estás seguro de querer actualizar este CPU?'),

                Link::make('Cancelar')
                    ->icon('close')
                    ->route('platform.cpu.list')
                    ->type(Color::DEFAULT()),
            ])->autoWidth(),
        ];
    }
}
