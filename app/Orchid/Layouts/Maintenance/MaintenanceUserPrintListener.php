<?php

namespace App\Orchid\Layouts\Maintenance;

use App\Models\Printer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class MaintenanceUserPrintListener extends Listener
{
    /**
     * List of field names for which values will be joined with targets' upon trigger.
     *
     * @var string[]
     */
    protected $extraVars = [];

    /**
     * List of field names for which values will be listened.
     *
     * @var string[]
     */
    protected $targets = [
        'user_id_owner',
        'printer_id',
    ];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * The name of the method must
     * begin with the prefix "async"
     *
     * @var string
     */
    protected $asyncMethod = 'asyncPrinterUser';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
        return [
            Layout::rows([

            Select::make('user_id_owner')
                ->title('Usuario propietario')
                ->options(
                    function () {
                        $options = [];
                        $options[0] = 'Sin propietario';
                        foreach (User::whereNotNull('printer_id')->get() as $user) {
                            $options[$user->id] = $user->name;
                        }
                        return $options;
                    }

                )
                ->empty($this->query->has('userOwner') ? $this->query->get('userOwner')->name : 'Sin propietario', $this->query->has('userOwner') ? $this->query->get('userOwner')->id : 0)
                //Si existe un usuario propietario y tiene permiso para editar solo el estado, se deshabilita el campo
                ->disabled($this->query->has('userOwner') && Auth::user()->hasAccess('platform.maintenance.editOnly') ? true : false)
                ->help('Usuario propietario del equipo'),


            Input::make('_printer')
                ->title('Impresora')
                ->readonly()
                ->value($this->query->has('userOwner') ? ($this->query->get('maintenance')->printer->model->brand->brand_name . ' ' . $this->query->get('maintenance')->printer->model->model . ' / ' . $this->query->get('maintenance')->printer->inventory_number) : 'Sin impresora')
                ->help('Impresora a la que pertenece el mantenimiento'),

            Input::make('printer_id')
                ->value($this->query->has('userOwner') ? $this->query->get('maintenance')->printer->id : null)
                ->type('hidden'),


            ]),

        ];
    }
}

