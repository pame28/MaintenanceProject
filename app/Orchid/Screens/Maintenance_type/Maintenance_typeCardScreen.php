<?php

namespace App\Orchid\Screens\Maintenance_type;

use App\Models\Maintenance_type;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class Maintenance_typeCardScreen extends Screen
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
        return $this->maintenance_type->type;
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
            Layout::legend('maintenance_type',[
                Sight::make('id', 'ID'),
                Sight::make('type','Tipo de Mantenimiento'),
                Sight::make('description', 'Descripción'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Maintenance_type $maintenance_type) {
                        return $maintenance_type->created_at->toDateTimeString();
                    }),
                sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Maintenance_type $maintenance_type) {
                        return $maintenance_type->updated_at->toDateTimeString();
                    }),
            ])->title('Información del Tipo de Mantenimiento'),
        ];
    }
}
