<?php

namespace App\Orchid\Screens\Cpu;

use App\Models\Cpu;
use App\Models\User;
use App\Orchid\Layouts\Brand\BrandEditRowsLayout;
use App\Orchid\Layouts\Cpu\CpuEditRowsLayout;
use App\Orchid\Layouts\Cpu\CpuUserOwnerListener;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CpuEditScreen extends Screen
{

    public $cpu;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Cpu $cpu): iterable
    {
        $userOwnerExist = User::where('cpu_id', $cpu->id)->exists() && $cpu->id != null;
        if($userOwnerExist)
        {return [
            'cpu' => $cpu,
            'userOwner' => User::where('cpu_id', $cpu->id)->first(),
        ];}
        else
        {
            return [
                'cpu' => $cpu,
            ];
        }
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->cpu->exists ? 'Editar CPU' : 'Crear CPU';
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
                'platform.cpu.edit',
                'systems.admin',
                'platform.cpu.editWithout',
            ];
        }
        return [
            'platform.cpu.create',
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
            CpuUserOwnerListener::class,
            CpuEditRowsLayout::class,
        ];
    }

    public function asyncCpuUserOwner(User $idUserOwner = null)
    {
        $department = $idUserOwner->department->department_name ?? 'Sin departamento asignado';

        return [
            'idUserOwner' => [$idUserOwner->id => $idUserOwner->name ?? 'Sin propietario'],
            '_department' =>  $department,
        ];
    }

    /**
     * @param Cpu $cpu
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Cpu $cpu, Request $request){
        $assignUser = $request->input('idUserOwner');

        $newCpu = $request->validate([
            'inventory_number' => 'required|max:20|min:6',
            'serial_number' => 'required|max:20|min:6',
            'model_id' => 'required',
            'storage_capacity' => 'required|max:20|min:2',
            'ram' => 'required|max:20|min:2',
            'date_of_purchase' => 'required|date',
            'cpu_status' => 'required',
            'observation' => 'nullable',
        ],
    [
        'inventory_number.required' => 'El número de inventario del CPU es requerido',
        'inventory_number.max' => 'El número de inventario no puede ser mayor a 20 caracteres',
        'inventory_number.min' => 'El número de inventario no puede ser menor a 6 caracteres',
        'serial_number.required' => 'El número de serie del CPU es requerido',
        'serial_number.max' => 'El número de serie no puede ser mayor a 20 caracteres',
        'serial_number.min' => 'El número de serie no puede ser menor a 6 caracteres',
        'model_id.required' => 'El modelo del CPU es requerido',
        'storage_capacity.required' => 'La capacidad de almacenamiento es requerida',
        'storage_capacity.max' => 'La capacidad de almacenamiento no puede ser mayor a 20 caracteres',
        'storage_capacity.min' => 'La capacidad de almacenamiento no puede ser menor a 2 caracteres',
        'ram.required' => 'La memoria RAM del CPU es requerida',
        'ram.max' => 'La memoria RAM no puede ser mayor a 20 caracteres',
        'ram.min' => 'La memoria RAM no puede ser menor a 2 caracteres',
        'date_of_purchase.required' => 'La fecha de compra es requerida',
        'date_of_purchase.date' => 'La fecha de compra debe ser una fecha válida',
        'cpu_status.required' => 'El estado del CPU es requerido',
    ]);

        $cpu->fill($newCpu)->save();

        $logAction = new LogService();
        //Asignar un nuevo usuario
        if($assignUser != null){
            User::where('cpu_id', $cpu->id)->update(['cpu_id' => null]);
            User::where('id', $assignUser)->update(['cpu_id' => $cpu->id]);
            $logAction->logAction(auth()->user(), "PUT", "Se ha asignado el CPU con número de inventario: $cpu->inventory_number al usuario: ".User::find($assignUser)->name);
        }
        //Quitar el usuario asignado
        else if ($assignUser == 0){
            User::where('cpu_id', $cpu->id)->update(['cpu_id' => null]);
            $logAction->logAction(auth()->user(), "PUT", "Se ha quitado el CPU con número de inventario: $cpu->inventory_number al usuario: ".User::find($assignUser)->name);
        }
        if($cpu->wasRecentlyCreated){
            $logAction->logAction(auth()->user(), "POST", "Se ha creado el CPU con número de inventario: $cpu->inventory_number");
            Alert::info('Has creado el CPU');
        }
        else{
            $logAction->logAction(auth()->user(), "PUT", "Se ha actualizado el CPU con número de inventario: $cpu->inventory_number");
            Alert::info('Has actualizado el CPU');
        }
        return to_route('platform.cpu.list');
    }
}
