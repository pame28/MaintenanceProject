<?php

namespace App\Orchid\Layouts\Printer;

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

class PrinterEditRowsLayout extends Rows
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
                ->value(old('inventory_number', $this->query['printer']->inventory_number))
                ->popover('Número de inventario con el que se identifica e la Impresora, este es proporcionado por el departamento de secretaría.'),

            Input::make('serial_number')
                ->title('Número de serie')
                ->placeholder('ABC-123-XYZ')
                ->value(old('serial_number', $this->query['printer']->serial_number))
                ->popover('El número de serie es un código único que trae e la Impresora de fábrica, revisa la etiqueta de la Impresora para obtenerlo.'),

            Relation::make('model_id')
                ->title('Modelo')
                ->fromModel(Equipment_model::class, 'model')
                ->value(old('model_id', $this->query['printer']->model_id))
                ->placeholder('Seleccione el modelo de la Impresora'),

            Input::make('cartridge', 'Cartucho')
                ->title('Cartucho')
                ->placeholder('Cartucho de tinta')
                ->value(old('cartridge', $this->query['printer']->cartridge))
                ->popover('Cartucho de tinta'),

            Select::make('connection_type')
                ->title('Tipo de conexión')
                ->options([
                    'USB' => 'USB',
                    'Red' => 'Red',
                    'USB y Red' => 'USB y Red',
                ])
                ->value(old('connection_type', $this->query['printer']->connection_type))
                ->popover('El tipo de conexión es el medio por el cual se conecta e la Impresora a la red de la institución, por ejemplo: USB, Red, USB y Red.'),

            DateTimer::make('date_of_purchase')
                ->title('Fecha de compra')
                ->placeholder('Fecha de compra de la Impresora')
                ->value(old('date_of_purchase', $this->query['printer']->date_of_purchase))
                ->format('Y-m-d'),

            Select::make('printer_status')
                ->title('Estado')
                ->options([
                    'Disponible' => 'Disponible',
                    'Asignado' => 'Asignado',
                    'En mantenimiento' => 'En mantenimiento',
                    'Obsoleto' => 'Obsoleto',
                ])
                ->value(old('printer_status', $this->query['printer']->printer_status))
                ->popover('El estado de la Impresora puede ser: Disponible, En uso, En reparación, De baja.'),

            TextArea::make('observations')
                ->title('Observaciones')
                ->placeholder('Observaciones de la impresora')
                ->rows(10)
                ->value(old('observations', $this->query['printer']->observations))
                ->popover('Observaciones de la Impresora'),

            Group::make([
                Button::make('Guardar')
                    ->icon('check')
                    ->type(Color::SUCCESS())
                    ->method('createOrUpdate')
                    ->canSee(!$this->query['printer']->exists)
                    ->confirm('¿Estás seguro de querer guardar esta Impresora?'),

                Button::make('Actualizar')
                    ->icon('check')
                    ->type(Color::PRIMARY())
                    ->method('createOrUpdate')
                    ->canSee($this->query['printer']->exists)
                    ->confirm('¿Estás seguro de querer actualizar esta Impresora?'),

                Link::make('Cancelar')
                    ->icon('close')
                    ->route('platform.printer.list')
                    ->type(Color::DEFAULT()),
            ])->autoWidth(),
    ];
    }

}
