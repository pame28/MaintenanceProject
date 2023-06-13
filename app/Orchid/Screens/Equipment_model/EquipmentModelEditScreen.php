<?php

namespace App\Orchid\Screens\Equipment_model;

use App\Models\Equipment_model;
use App\Orchid\Layouts\Equipment_model\EquipmentModelEditRowsLayout;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class EquipmentModelEditScreen extends Screen
{
    public $modelo;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Equipment_model $modelo): iterable
    {
        return [
            'modelo' => $modelo,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->modelo->exists ? 'Editar Modelo' : 'Crear Modelo';
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
                'platform.model.edit',
                'systems.admin'
            ];
        }
        return [
            'platform.model.create',
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
            EquipmentModelEditRowsLayout::class,
        ];
    }

    /**
     * @param Equipment_model $modelo
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Equipment_model $modelo, Request $request)
    {
        $newModel = $request->validate([
            'model' => 'required|max:100|min:2',
            'brand_id' => 'required',
        ],
        [
            'model.required' => 'El campo modelo es obligatorio',
            'model.max' => 'El campo modelo no puede tener más de 100 caracteres',
            'model.min' => 'El campo modelo no puede tener menos de 2 caracteres',
            'brand_id.required' => 'El campo marca es obligatorio',
        ]);

        $logService = new LogService();

        if($modelo->exists){
            $modelo->update($newModel);
            $logService->logAction(auth()->user(), 'PUT', "El modelo $modelo->model se ha actualizado correctamente");
            Alert::info('El modelo se ha actualizado correctamente');
        }else{
            $modelo->create($newModel);
            $logService->logAction(auth()->user(), 'POST', "El modelo $modelo->model se ha creado correctamente");
            Alert::info('El modelo se ha creado correctamente');
        }

        return redirect()->route('platform.model.list');
    }
}
