<?php

namespace App\Orchid\Screens\Department;

use App\Models\Department;
use App\Orchid\Layouts\Department\DepartmentEditRowsLayout;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class DepartmentEditScreeen extends Screen
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
        return $this->department->exists ? 'Editar Departamento' : 'Crear Departamento';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        if(request()->segment(2) != null){
            return [
                'platform.department.edit',
                'systems.admin'
            ];
        }
        return [
            'platform.department.create',
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
            DepartmentEditRowsLayout::class,
        ];
    }

    /**
     * @param Department    $department
     * @param Request       $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Department $department, Request $request)
    {
        $newDepartment = $request->validate([
            'department_name' => 'required|max:100|min:2',
            'description' => 'required|max:200|min:2',
        ],[
            'department_name.required' => 'El nombre no puede estar vacío',
            'department_name.max' => 'El nombre no puede tener más de 100 caracteres',
            'department_name.min' => 'El nombre no puede tener menos de 2 caracteres',
            'description.required' => 'La descripción no puede estar vacía',
            'description.max' => 'La descripción no puede tener más de 200 caracteres',
            'description.min' => 'La descripción no puede tener menos de 2 caracteres',
        ]);

        $logService = new LogService();
        if($department->exists) {
            $department->update($newDepartment);
            $logService->logAction(auth()->user(), 'PUT', "Ha actualizado el departamento ".$department->department_name);
            Alert::info('Has actualizado el departamento');
        }else{
            $department->create($newDepartment);
            $logService->logAction(auth()->user(), 'POST', "Ha creado el departamento ".$department->department_name);
            Alert::info('Has creado el departamento');
        }

        return redirect()->route('platform.department.list');
    }

}
