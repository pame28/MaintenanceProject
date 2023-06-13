<?php

namespace App\Orchid\Screens\Department;

use App\Models\Department;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class DepartmentCardScreen extends Screen
{
    public $department;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Department $department): iterable
    {
        return [
            'department' => $department,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Departamento de' . $this->department->department_name;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.department.list',
            'systems.admin'
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('department',[
                Sight::make('id', 'ID'),
                Sight::make('department_name','Nombre del Departamento'),
                Sight::make('description', 'Descripción'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Department $department) {
                        return $department->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Department $department) {
                        return $department->updated_at->toDateTimeString();
                    }),
            ])->title('Información del Departamento'),
        ];
    }
}
