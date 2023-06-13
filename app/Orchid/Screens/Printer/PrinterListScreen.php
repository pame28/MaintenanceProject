<?php

namespace App\Orchid\Screens\Printer;

use App\Models\Printer;
use App\Orchid\Layouts\Printer\PrinterListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PrinterListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'printers' => Printer::select('printers.*', 'equipment_models.model', 'brands.brand_name', 'users.name', 'departments.department_name')
                ->join('equipment_models', 'equipment_models.id', '=', 'printers.model_id')
                ->join('brands', 'brands.id', '=', 'equipment_models.brand_id')
                ->leftJoin('users', 'users.printer_id', '=', 'printers.id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department_id')
                ->filters()
                ->defaultSort('id')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Impresoras';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.printer.list',
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
            Link::make('Crear Impresora')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.printer.create', 'systems.admin']))
                ->route('platform.printer.edit', ['printer' => new Printer()]),
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
            PrinterListTableLayout::class,
        ];
    }

    /**
     * @param Printer $printer
     *
     * @throws \Exception
     */
    public function delete(Printer $printer)
    {
        $userOwnerExist = $printer->userOwner()->exists();
        if($userOwnerExist){
            $printer->userOwner()->update(['printer_id' => null]);
        }

        $printer->delete();
        $logAction = new LogService();
        $logAction->logAction(auth()->user(), 'DELETe',"Eliminó la impresora " . $printer->printer_name);
        Alert::info('La impresora ha sido eliminada.');
    }
}
