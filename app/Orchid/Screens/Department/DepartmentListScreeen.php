<?php

namespace App\Orchid\Screens\Department;

use App\Models\Department;
use App\Orchid\Layouts\Department\DepartmentListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class DepartmentListScreeen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'departments' => Department::filters()->defaultSort('id')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Departamentos';
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
        return [
            Link::make('Nuevo Departamento')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.department.create', 'systems.admin']))
                ->route('platform.department.edit', ['department' => new Department()]),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            DepartmentListTableLayout::class,
        ];
    }

    public function delete(Department $department)
    {
        $department->delete();
        $logService = new LogService();
        $logService->logAction($department, 'DELETe', "Eliminó el departamento " . $department->name . " con ID " . $department->id);

        Alert::info('El departamento ha sido eliminado');
    }
}
