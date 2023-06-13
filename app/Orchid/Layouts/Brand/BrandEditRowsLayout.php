<?php

namespace App\Orchid\Layouts\Brand;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;

class BrandEditRowsLayout extends Rows
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
            Input::make('brand_name')
                ->title('Nombre')
                ->placeholder('Nombre de la marca')
                ->value(old('brand_name', $this->query['brand']->brand_name))
                ->help('Máximo 100 caracteres'),

            TextArea::make('description')
                ->title('Descripción')
                ->rows(4)
                ->maxlength(200)
                ->value(old('description', $this->query['brand']->description))
                ->placeholder('Descripción de la marca'),

            Group::make([
                Button::make('Guardar')
                    ->icon('check')
                    ->type(Color::SUCCESS())
                    ->method('createOrUpdate')
                    ->canSee(!$this->query['brand']->exists)
                    ->confirm('¿Estás seguro de querer guardar esta marca?'),

                Button::make('Actualizar')
                    ->icon('check')
                    ->type(Color::PRIMARY())
                    ->method('createOrUpdate')
                    ->canSee($this->query['brand']->exists)
                    ->confirm('¿Estás seguro de querer actualizar esta marca?'),

                Link::make('Cancelar')
                    ->icon('close')
                    ->route('platform.brand.list')
                    ->type(Color::DEFAULT()),
            ])->autoWidth(),
        ];
    }
}
