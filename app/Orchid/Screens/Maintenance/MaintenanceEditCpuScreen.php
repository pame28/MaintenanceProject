<?php

namespace App\Orchid\Screens\Maintenance;

use App\Models\Brand;
use App\Models\Cpu;
use App\Models\Equipment_model;
use App\Models\Maintenance;
use App\Models\Printer;
use App\Models\User;
use App\Orchid\Layouts\AmountListener;
use App\Orchid\Layouts\Maintenance\MaintenanceEditDetallesRowsLayout;
use App\Orchid\Layouts\Maintenance\MaintenanceEditRowsLayout;
use App\Orchid\Layouts\Maintenance\MaintenanceUserCpuListener;
use App\Orchid\Layouts\Maintenance\MaintenanceUserPrintListener;
use App\Orchid\Layouts\Printer\PrinterUserOwnerListener;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class MaintenanceEditCpuScreen extends Screen
{

    public $maintenance;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Maintenance $maintenance): iterable
    {
        $userOwnerExist = $maintenance->user_id_owner != null;
        if($userOwnerExist){
            return [
                'maintenance' => $maintenance,
                'userOwner' => User::find($maintenance->user_id_owner),
            ];
        }else{
            return [
                'maintenance' => $maintenance,
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
        return $this->maintenance->exists ? 'Editar Mantenimiento del CPU' : 'Crear Mantenimiento de un CPU';
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
                'platform.maintenance.edit',
                'systems.admin',
                'platform.maintenance.editOnly',
            ];
        }else{
            return [
                'platform.maintenance.create',
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
        $user = $this->maintenance->user ?? auth()->user();
        return [
            Layout::rows([
                Input::make('_user_id')
                    ->title('Usuario encargado')
                    ->value($user->name)
                    ->disabled()
                    ->help('Usuario encargado del mantenimiento'),

                Input::make('user_id')
                    ->type('hidden')
                    ->value($user->id),
                ]),

            MaintenanceUserCpuListener::class,
            MaintenanceEditDetallesRowsLayout::class,

            Layout::rows([
                Group::make([
                    Button::make('Guardar')
                        ->icon('icon-check')
                        ->method('save')
                        ->canSee(true),

                    Link::make('Cancelar')
                        ->route('platform.maintenance.list'),
                ])->autoWidth(),
            ])
        ];
    }

    public function asyncCpuUser(User $user_id_owner = null)
    {
        $cpu = Cpu::find($user_id_owner->cpu_id);
        return [
            'user_id_owner' => [$user_id_owner->id, $user_id_owner->name],
            'cpu_id' => $cpu->id,
            '_cpu' => $cpu->model->brand->brand_name . ' '. $cpu->model->model . ' / ' . $cpu->inventory_number,
        ];

    }

    public function save(Request $request,Maintenance $maintenance)
    {
        if(Auth::user()->hasAccess('platform.maintenance.editOnly') && $maintenance->exists){
            $request['user_id_owner'] = 1;
            $request['maintenance_type_id'] = 1;
        }
        $newMaintenance = $request->validate([
            'user_id' => 'required',
            'user_id_owner' => 'required',
            'cpu_id' => 'required',
            'maintenance_type_id' => 'required',
            'description' => 'required|min:5',
            'solution' => 'required|min:5',
            'status' => 'required',

        ],[
            'user_id.required' => 'El usuario no puede estar vacío',
            'description.required' => 'La descripción no puede estar vacía',
            'description.min' => 'El campo descripción debe tener al menos 5 caracteres',
            'solution.required' => 'La solución no puede estar vacía',
            'solution.min' => 'El campo solución debe tener al menos 5 caracteres',
            'status.required' => 'El estado del mantenimiento no puede estar vacío',
        ]);

        if(Auth::user()->hasAccess('platform.maintenance.editOnly') && $maintenance->exists){
            unset($newMaintenance['user_id_owner']);
            unset($newMaintenance['maintenance_type_id']);
        }

        $maintenance->fill($newMaintenance)->save();
        Cpu::find($maintenance->cpu_id)->update(['last_revised_date' => $maintenance->updated_at]);
        Cpu::find($maintenance->cpu_id)->update(['last_revised_user_id' => $maintenance->user_id]);

        $logService = new LogService();
        if($maintenance->wasRecentlyCreated){
            Alert::info('El mantenimiento se ha creado correctamente');
            $logService->logAction(auth()->user(), 'POST', "Mantenimiento creado: " . $maintenance->id);
        }
        else{
            Alert::info('El mantenimiento se ha actualizado correctamente');
            $logService->logAction(auth()->user(), 'PUT', "Mantenimiento actualizado: " . $maintenance->id);
        }

        return redirect()->route('platform.maintenance.list');
    }
}
