<?php

namespace App\Orchid\Screens\Printer;

use App\Models\Printer;
use App\Models\User;
use App\Orchid\Layouts\Printer\PrinterEditRowsLayout;
use App\Orchid\Layouts\Printer\PrinterUserOwnerListener;
use App\Orchid\Layouts\Printer\PrinterUserOwnerPUTListener;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PrinterEditScreen extends Screen
{

    public $printer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Printer $printer): iterable
    {
        $userOwnerExist = User::where('printer_id', $printer->id)->exists() && $printer->id != null;
        if($userOwnerExist)
        {return [
            'printer' => $printer,
            'userOwner' => User::where('printer_id', $printer->id)->first(),
        ];}
        else
        {
            return [
                'printer' => $printer,
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
        return $this->printer->exists ? 'Editar Impresora' : 'Crear Impresora';
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
                'platform.printer.edit',
                'systems.admin',
                'platform.printer.editWithout'
            ];
        }
        else{
            return [
                'platform.printer.create',
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
            PrinterUserOwnerListener::class,
            PrinterEditRowsLayout::class,
        ];
    }


    public function asyncPrinterUserOwner(User $idUserOwner = null)
    {
        $department = $idUserOwner->department->department_name ?? 'Sin departamento asignado';

        return [
            'idUserOwner' => [$idUserOwner->id => $idUserOwner->name ?? 'Sin propietario'],
            '_department' =>  $department,
        ];
    }

    public function createOrUpdate(Printer $printer, Request $request){
        $assignUser = $request->input('idUserOwner');
        $newPrinter = $request->validate([
            'inventory_number' => 'required|max:20|min:6',
            'serial_number' => 'required|max:20|min:6',
            'model_id' => 'required',
            'cartridge' => 'required',
            'connection_type' => 'required',
            'date_of_purchase' => 'required|date',
            'printer_status' => 'required',
            'observations' => 'nullable',
        ],[
            'inventory_number.required' => 'El número de inventario es requerido',
            'inventory_number.max' => 'El número de inventario no puede tener más de 20 caracteres',
            'inventory_number.min' => 'El número de inventario no puede tener menos de 6 caracteres',
            'serial_number.required' => 'El número de serie es requerido',
            'serial_number.max' => 'El número de serie no puede tener más de 20 caracteres',
            'serial_number.min' => 'El número de serie no puede tener menos de 6 caracteres',
            'model_id.required' => 'El modelo es requerido',
            'cartridge.required' => 'El cartucho es requerido',
            'connection_type.required' => 'El tipo de conexión es requerido',
            'date_of_purchase.required' => 'La fecha de compra es requerida',
            'date_of_purchase.date' => 'La fecha de compra debe ser una fecha válida',
            'printer_status.required' => 'El estado es requerido',
        ]);
        $printer->fill($newPrinter)->save();

        $logAction = new LogService();
        //Asignar un nuevo usuario
        if($assignUser != null){
            User::where('printer_id', $printer->id)->update(['printer_id' => null]);
            User::where('id', $assignUser)->update(['printer_id' => $printer->id]);
            $logAction->logAction(auth()->user(), 'PUT', 'Impresora' . $printer->id .",". $printer->inventory_number . 'Se ha asignado la impresora a un nuevo usuario');
        }
        //Quitar el usuario asignado
        else if ($assignUser == 0){
            User::where('printer_id', $printer->id)->update(['printer_id' => null]);
            $logAction->logAction(auth()->user(), 'PUT', 'Impresora' . $printer->id .",". $printer->inventory_number . 'Se ha quitado el usuario asignado a la impresora');
        }

        if($printer->wasRecentlyCreated){
            Alert::info('Has creado una nueva impresora');
            $logAction->logAction(auth()->user(), 'POST', 'Impresora' . $printer->id .",". $printer->inventory_number . 'Se ha creado una nueva impresora');
        }
        else{
            Alert::info('Has editado la impresora');
            $logAction->logAction(auth()->user(), 'PUT', 'Impresora' . $printer->id .",". $printer->inventory_number . 'Se ha editado la impresora');
        }

        return to_route('platform.printer.list');
    }
}
