<?php

namespace App\Orchid\Layouts\Maintenance;

use App\Models\Maintenance;
use App\Models\Printer;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class MaintenanceListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'maintenances';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'No.')
                ->sort()
                ->render(function (Maintenance $maintenance) {
                    return Link::make($maintenance->id)
                        ->route('platform.maintenance.card', $maintenance);
                })
                ->filter(),
            TD::make('type_equipment', 'Tipo de Equipo')
                ->render(function (Maintenance $maintenance) {
                    return $maintenance->cpu_id ? 'CPU' : 'Impresora';
                }),

            TD::make('maintenance_type_id', 'Tipo de Mantenimiento')
                ->sort()
                ->filter(Select::make()
                    ->options(
                        function () {
                            $options = [];
                            $options[''] = 'Selecciona';
                            foreach (Maintenance::select('maintenance_type_id')->distinct()->get() as $maintenance) {
                                $options[$maintenance->maintenance_type_id] = $maintenance->maintenance_type->type;
                            }
                            return $options;
                        }
                    )
                )
                ->render(function (Maintenance $maintenance) {
                    return $maintenance->maintenance_type->type;
                }),

            TD::make('description', 'Descripción')
                ->render(
                    function (Maintenance $maintenance) {
                        return substr($maintenance->description, 0, 50) . '...';
                    }
                )
                ->filter(Input::make()),

            TD::make('user_id', 'Realizado por')
                ->sort()
                ->filter(Select::make()
                    ->options(
                        function () {
                            $options = [];
                            $options[''] = 'Selecciona';
                            foreach (Maintenance::select('user_id')->distinct()->get() as $maintenance) {
                                $options[$maintenance->user_id] = $maintenance->user->name;
                            }
                            return $options;
                        }
                    )
                )
                ->render(function (Maintenance $maintenance) {
                    return $maintenance->user->name;
                }),

            TD::make('user_id_owner', 'Usuario Propietario')
                ->sort()
                ->filter(Select::make()
                    ->options(
                        function () {
                            $options = [];
                            $options[''] = 'Selecciona';
                            foreach (Maintenance::select('user_id_owner')->distinct()->get() as $maintenance) {
                                $options[$maintenance->user_id_owner] = $maintenance->user_owner->name;
                            }
                            return $options;
                        }
                    )
                )
                ->render(function (Maintenance $maintenance) {
                    return $maintenance->user_owner->name;
                }),


            TD::make('inventory_number', 'Número de Inventario')
                //->filter(InventoryFilterLayout::class)
                ->render(function (Maintenance $maintenance) {
                    return $maintenance->cpu_id ? $maintenance->cpu->inventory_number : $maintenance->printer->inventory_number;
                }),
            TD::make('status', 'Estado')
                ->sort()
                ->filter(
                    Select::make()
                        ->options([
                            '' => 'Selecciona',
                            'Pendiente' => 'Pendiente',
                            'En Proceso' => 'En Proceso',
                            'Finalizado' => 'Finalizado',
                        ])
                ),

            TD::make('acciones', 'Acciones')
                ->render(fn (Maintenance $maintenance) => DropDown::make()
                ->icon('options-vertical')
                ->list([
                    Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.maintenance.card', $maintenance),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(
                                (Auth::user()->hasAnyAccess(['platform.maintenance.edit', 'platform.maintenance.editOnly'])
                                    && $maintenance->user_id == Auth::user()->id)
                                    || Auth::user()->hasAccess('systems.admin')
                                )
                            ->route(($maintenance->cpu_id ? 'platform.maintenanceCpu.edit' : 'platform.maintenancePrinter.edit'), $maintenance),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['cpu' => $maintenance->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.maintenance.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar este Mantenimiento?'),
                ])),

        ];
    }
}
