<?php

namespace App\Orchid\Layouts\Equipment_model;

use App\Models\Brand;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class EquipmentModelEditRowsLayout extends Rows
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
            Relation::make('brand_id')
                ->title('Marca')
                ->fromModel(Brand::class, 'brand_name')
                ->required()
                ->value(old('brand_id', $this->query['modelo']->brand_id))
                ->placeholder('Seleccione la marca del modelo'),

            Input::make('model')
                ->title('Modelo')
                ->placeholder('Nombre del modelo')
                ->value(old('model', $this->query['modelo']->model))
                ->help('Máximo 100 caracteres'),

                Group::make([
                    Button::make('Guardar')
                        ->icon('check')
                        ->type(Color::SUCCESS())
                        ->method('createOrUpdate')
                        ->canSee(!$this->query['modelo']->exists)
                        ->confirm('¿Estás seguro de querer guardar este modelo?'),

                    Button::make('Actualizar')
                        ->icon('check')
                        ->type(Color::PRIMARY())
                        ->method('createOrUpdate')
                        ->canSee($this->query['modelo']->exists)
                        ->confirm('¿Estás seguro de querer actualizar este modelo?'),

                    Link::make('Cancelar')
                        ->icon('close')
                        ->route('platform.model.list')
                        ->type(Color::DEFAULT()),
                ])->autoWidth(),
        ];
    }
}
