<?php

namespace App\Orchid\Screens\Cpu;

use App\Models\Cpu;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class CpuCardScreen extends Screen
{
    public $cpu;
    public $userOwner;
    public $userOwnerDepartment;
    public $userResponsible;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Cpu $cpu): iterable
    {
        return [
            'cpu' => $cpu,
            'model' => $cpu->model,
            'brand' => $cpu->model->brand,
            'userOwner' => $cpu->userOwner,
            'userResponsible' => User::find($cpu->last_revised_user_id),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Cpu ' . $this->cpu->model->brand->brand_name . ' ' . $this->cpu->model->model . ' / ' . $this->cpu->inventory_number;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.cpu.edit',
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
            Layout::legend('cpu',[
                Sight::make('id', 'ID'),
                Sight::make('serial_number', 'Número de serie'),
                Sight::make('inventory_number', 'Número de inventario'),
                Sight::make('model.brand.brand_name', 'Marca'),
                Sight::make('model.model', 'Modelo'),
                Sight::make('storage_capacity', 'Capacidad de almacenamiento'),
                Sight::make('ram' , 'Memoria RAM'),
                Sight::make('cpu_status', 'Estado del CPU'),
                Sight::make('observations', 'Observaciones'),
                Sight::make('date_of_purchase', 'Fecha de compra'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Cpu $cpu) {
                        return $cpu->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Cpu $cpu) {
                        return $cpu->updated_at->toDateTimeString();
                    }),
            ])->title('Información del CPU'),

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
                        return Cpu::find($this->cpu->id)->last_revised_date;
                    }),
            ])->title('Usuario Responsable del Último Mantenimiento')->canSee($this->userResponsible != null),
        ];
    }
}
