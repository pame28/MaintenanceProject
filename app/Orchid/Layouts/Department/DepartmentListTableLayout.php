<?php

namespace App\Orchid\Layouts\Department;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class DepartmentListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'departments';

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
                ->render(function (Department $department) {
                    return Link::make($department->id)
                        ->route('platform.department.card', $department);
                })
                ->filter(Input::make()),
            TD::make('department_name', 'Departamento')
                ->sort()
                ->filter(Input::make()),
            TD::make('description', 'Descripción')
                ->render( function (Department $department) {
                    return substr($department->description, 0, 50) . '...';
                    }
                )
                ->sort(),
            TD::make('acciones', 'Acciones')
                ->render(fn (Department $department) => DropDown::make()
                ->align(TD::ALIGN_CENTER)
                ->icon('options-vertical')
                ->list([
                    Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.department.card', $department),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.department.edit', 'systems.admin']))
                            ->route('platform.department.edit', $department),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['department' => $department->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.department.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar este departamento?'),
                ])),
        ];
    }
}
