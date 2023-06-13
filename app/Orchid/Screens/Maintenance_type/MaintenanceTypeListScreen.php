<?php

namespace App\Orchid\Screens\Maintenance_type;

use App\Models\Maintenance_type;
use App\Orchid\Layouts\Maintenance_type\MaintenanceTypeListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class MaintenanceTypeListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'types' => Maintenance_type::filters()->defaultSort('id')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Tipos de mantenimiento';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.maintenance_type.list',
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
            Link::make('Crear tipo de mantenimiento')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.maintenance_type.create', 'systems.admin']))
                ->route('platform.maintenance_type.edit', ['maintenance_type' => new Maintenance_type()])
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
            MaintenanceTypeListTableLayout::class,
        ];
    }

    /**
     * @param Maintenance_type $maintenance_type
     *
     * @throws \Exception
     */
    public function delete(Maintenance_type $maintenance_type)
    {
        $maintenance_type->delete();
        $logAction = new LogService();
        $logAction->logAction(auth()->user(), 'DELETe'. "Eliminó el tipo de mantenimiento". $maintenance_type->name);
        Alert::info('El tipo de mantenimiento ha sido eliminado.');
    }
}
