<?php

namespace App\Orchid\Screens\Maintenance;

use App\Models\Cpu;
use App\Models\Maintenance;
use App\Models\Printer;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class MaintenanceCardScreen extends Screen
{
    public $maintenance;
    public $printer;
    public $cpu;
    public $userOwner;
    public $userResponsible;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Maintenance $maintenance): iterable
    {
        return [
            'maintenance' => $maintenance,
            'printer' => Printer::find($maintenance->printer_id),
            'cpu' => Cpu::find($maintenance->cpu_id),
            'model' => $maintenance->cpu_id ? $maintenance->cpu->model : $maintenance->printer->model,
            'brand' => $maintenance->cpu_id ? $maintenance->cpu->model->brand : $maintenance->printer->model->brand,
            'userOwner' => $maintenance->cpu_id ? User::find($maintenance->user_id_owner) : User::find($maintenance->user_id_owner),
            'userResponsible' => $maintenance->cpu_id ? User::find($maintenance->user_id) : User::find($maintenance->user_id),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->maintenance->cpu_id ? 'Mantenimiento de Cpu ' . $this->maintenance->cpu->model->brand->brand_name . ' ' . $this->maintenance->cpu->model->model . ' / ' . $this->maintenance->cpu->inventory_number : 'Mantenimiento de Impresora ' . $this->maintenance->printer->model->brand->brand_name . ' ' . $this->maintenance->printer->model->model . ' / ' . $this->maintenance->printer->inventory_number;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable {
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
            Layout::legend('maintenance',[
                Sight::make('id', 'ID'),
                Sight::make('maintenance_type_id', 'Tipo de Mantenimiento')
                    ->render(function (Maintenance $maintenance) {
                        return $maintenance->maintenance_type->type;
                    }),
                Sight::make('description', 'Descripción del Problema'),
                Sight::make('solution', 'Solución del Problema'),
                Sight::make('status', 'Estado'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Maintenance $maintenance) {
                        return $maintenance->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Maintenance $maintenance) {
                        return $maintenance->updated_at->toDateTimeString();
                    }),
            ])->title('Datos del Mantenimiento'),

            Layout::legend('cpu',[
                Sight::make('id', 'Equipo')
                    ->render(function (Cpu $cpu) {
                        return $cpu->model->brand->brand_name . ' ' . $cpu->model->model;
                    }),
                Sight::make('inventory_number', 'Número de Inventario'),
                Sight::make('serial_number', 'Número de Serie'),
            ])->title('Datos del Cpu')->canSee($this->maintenance->cpu_id ? true : false),

            Layout::legend('printer',[
                Sight::make('id', 'Equipo')
                    ->render(function (Printer $printer) {
                        return $printer->model->brand->brand_name . ' ' . $printer->model->model;
                    }),
                Sight::make('inventory_number', 'Número de Inventario'),
                Sight::make('serial_number', 'Número de Serie'),
            ])->title('Datos de la Impresora')->canSee($this->maintenance->cpu_id ? false : true),

            Layout::legend('userOwner',[
                Sight::make('id', 'ID'),
                Sight::make('name', 'Nombre del usuario'),
                Sight::make('department_id', 'Departamento')
                    ->render(function (User $user) {
                        return $user->department->department_name;
                    }),
            ])->title('Usuario Propietario'),

            Layout::legend('userResponsible',[
                Sight::make('id', 'ID'),
                Sight::make('name', 'Nombre del usuario'),
                Sight::make('email', 'Correo electrónico'),
                Sight::make('department_id', 'Departamento')
                    ->render(function (User $user) {
                        return $user->department->department_name;
                    }),
                Sight::make('created_at', 'Fecha de Creación del Usuario')
                    ->render(function (User $user) {
                        return $user->created_at->toDateTimeString();
                    }),
            ])->title('Usuario Responsable del Mantenimiento'),
        ];
    }
}
