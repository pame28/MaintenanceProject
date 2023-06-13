<?php

namespace App\Orchid\Screens\Maintenance_type;

use App\Models\Maintenance_type;
use App\Orchid\Layouts\Maintenance_type\MaintenanceTypeEditRowsLayout;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class MaintenanceTypeEditScreen extends Screen
{

    public $maintenance_type;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Maintenance_type $maintenance_type): iterable
    {
        return [
            'maintenance_type' => $maintenance_type,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->maintenance_type->exists ? 'Editar tipo de mantenimiento' : 'Crear tipo de mantenimiento';
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
                'platform.maintenance_type.edit',
                'systems.admin'
            ];
        }
        else{
            return [
                'platform.maintenance_type.create',
                'systems.admin'
            ];
        }
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
            MaintenanceTypeEditRowsLayout::class,
        ];
    }

    /**
     * @param Maintenance_type $maintenance_type
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Maintenance_type $maintenance_type, Request $request)
    {
        $newType = $request->validate([
            'type' => 'required|max:100|min:2',
            'description' => 'required|max:200|min:2',
        ],
        [
            'type.required' => 'El tipo de mantenimiento es obligatorio',
            'type.max' => 'El tipo de mantenimiento no puede tener más de 100 caracteres',
            'type.min' => 'El tipo de mantenimiento no puede tener menos de 2 caracteres',
            'description.required' => 'La descripción del mantenimiento es obligatoria',
            'description.max' => 'La descripción del mantenimiento no puede tener más de 200 caracteres',
            'description.min' => 'La descripción del mantenimiento no puede tener menos de 2 caracteres',
        ]);

        $logAction = new LogService();
        if($maintenance_type->exists){
            $maintenance_type->update($newType);
            $logAction->logAction(auth()->user(), 'PUT', "El tipo de mantenimiento " .$maintenance_type->type . " se ha actualizado correctamente");
            Alert::info('El tipo de mantenimiento se ha actualizado correctamente');
        }else{
            $maintenance_type->create($newType);
            $logAction->logAction(auth()->user(), 'POST', "El tipo de mantenimiento " .$maintenance_type->type . " se ha creado correctamente");
            Alert::info('El tipo de mantenimiento se ha creado correctamente');
        }

        return redirect()->route('platform.maintenance_type.list');
    }
}
