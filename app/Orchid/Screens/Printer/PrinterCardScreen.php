<?php

namespace App\Orchid\Screens\Printer;

use App\Models\Printer;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class PrinterCardScreen extends Screen
{
    public $printer;
    public $userOwner;
    public $userOwnerDepartment;
    public $userResponsible;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Printer $printer): iterable
    {
        return [
            'printer' => $printer,
            'model' => $printer->model,
            'brand' => $printer->model->brand,
            'userOwner' => $printer->userOwner,
            'userResponsible' => User::find($printer->last_revised_user_id),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Impresora ' . $this->printer->model->brand->brand_name . ' ' . $this->printer->model->model . ' / ' . $this->printer->inventory_number;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable {
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
            Layout::legend('printer',[
                Sight::make('id', 'ID'),
                Sight::make('serial_number', 'Número de serie'),
                Sight::make('inventory_number', 'Número de inventario'),
                Sight::make('model.brand.brand_name', 'Marca'),
                Sight::make('model.model', 'Modelo'),
                Sight::make('cartridge', 'Cartucho'),
                Sight::make('connection_type', 'Tipo de conexión'),
                Sight::make('printer_status', 'Estado de la impresora'),
                Sight::make('observations', 'Observaciones'),
                Sight::make('date_of_purchase', 'Fecha de compra'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Printer $printer) {
                        return $printer->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Printer $printer) {
                        return $printer->updated_at->toDateTimeString();
                    }),
            ])->title('Información de la Impresora'),

            Layout::legend('userOwner',[
                Sight::make('id', 'ID'),
                Sight::make('name', 'Nombre del usuario'),
                Sight::make('department_id', 'Departamento')
                    ->render(function (User $user) {
                        return $user->department->department_name;
                    }),
            ])->title('Usuario Propietario')->canSee($this->userOwner != null),

            Layout::legend('userResponsible',[
                Sight::make('id', 'ID'),
                Sight::make('name', 'Nombre del usuario'),
                Sight::make('email', 'Correo electrónico'),
                Sight::make('department_id', 'Departamento')
                    ->render(function (User $user) {
                        return $user->department->department_name;
                    }),
                Sight::make('last_revised_at', 'Fecha en que se realizó el mantenimiento')
                    ->render(function (User $user) {
                        return Printer::find($this->printer->id)->last_revised_date;
                    }),
            ])->title('Usuario Responsable del Último Mantenimiento')->canSee($this->userResponsible != null),
        ];
    }
}
