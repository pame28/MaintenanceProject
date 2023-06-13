<?php

namespace App\Orchid\Screens\Maintenance;

use App\Models\Maintenance;
use App\Orchid\Filters\InventoryFilter;
use App\Orchid\Layouts\Maintenance\InventoryFilterLayout;
use App\Orchid\Layouts\Maintenance\MaintenanceListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class MaintenanceListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'maintenances' => Maintenance::with('maintenance_type', 'user', 'user_owner', 'cpu', 'printer')
            ->filters(InventoryFilterLayout::class)
            ->defaultSort('id', 'desc')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Lista de Mantenimientos';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.maintenance.list',
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
            DropDown::make('Crear Mantenimiento')
            ->icon('plus')
            ->canSee(Auth::user()->hasAnyAccess(['platform.maintenance.create', 'systems.admin']))
            ->list([
                Link::make('Mantenimiento a CPU')
                    ->icon('windows')
                    ->canSee(true)
                    ->route('platform.maintenanceCpu.edit', ['maintenance' => new Maintenance()]),

                Link::make('Mantenimiento a Impresora')
                    ->icon('printer')
                    ->canSee(true)
                    ->route('platform.maintenancePrinter.edit', ['maintenance' => new Maintenance()]),

            ])
            ,
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
            InventoryFilterLayout::class,
            MaintenanceListTableLayout::class,
        ];
    }

    /**
     * @param Maintenance $maintenance
     *
     * @throws \Exception
     */
    public function delete(Maintenance $maintenance)
    {
        $maintenance->delete();
        $logService = new LogService();
        $logService->logAction(auth()->user() , 'DELETe', "Eliminó el mantenimiento con ID " . $maintenance->id);
        Alert::info('El mantenimiento ha sido eliminado.');
    }
}
