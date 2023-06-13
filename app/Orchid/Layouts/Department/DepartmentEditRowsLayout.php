<?php

namespace App\Orchid\Layouts\Department;

use App\Models\Department;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;

class DepartmentEditRowsLayout extends Rows
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
            Input::make('department_name')
                ->title('Nombre')
                ->placeholder('Nombre del departamento')
                ->value(old('department_name', $this->query['department']->department_name))
                ->help('Máximo 100 caracteres'),

            TextArea::make('description')
                ->title('Descripción')
                ->rows(4)
                ->maxlength(200)
                ->value(old('description', $this->query['department']->description))
                ->placeholder('Descripción del departamento'),

            Group::make([
                Button::make('Guardar')
                    ->icon('check')
                    ->type(Color::SUCCESS())
                    ->method('createOrUpdate')
                    ->canSee(!$this->query['department']->exists)
                    ->confirm('¿Estás seguro de querer guardar este departamento?'),

                Button::make('Actualizar')
                    ->icon('check')
                    ->type(Color::PRIMARY())
                    ->method('createOrUpdate')
                    ->canSee($this->query['department']->exists)
                    ->confirm('¿Estás seguro de querer actualizar este departamento?'),

                Link::make('Cancelar')
                    ->icon('close')
                    ->type(Color::DEFAULT())
                    ->route('platform.department.list'),
            ])->autoWidth(),

        ];
    }
}
