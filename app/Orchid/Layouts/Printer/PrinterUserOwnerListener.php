<?php

namespace App\Orchid\Layouts\Printer;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class PrinterUserOwnerListener extends Listener
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
        'idUserOwner',
        '_department',
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
    protected $asyncMethod = 'asyncPrinterUserOwner';

    /**
     * @return Layout[]
     */
    protected function layouts(): iterable
    {
            return [
                Layout::rows([
                    Select::make('idUserOwner')
                        ->title('Usuario propietario')

                        ->options(
                            function () {
                                $options = [];
                                $options[0] = 'Sin propietario';
                                foreach (User::whereNull('printer_id')->get() as $user) {
                                    $options[$user->id] = $user->name;
                                }
                                return $options;
                            }

                        )
                        ->empty(!$this->query->has('userOwner') ? 'Sin propietario' : $this->query->get('userOwner')->name, !$this->query->has('userOwner') ? '0' : $this->query->get('userOwner')->id) //$this->query->get('userOwner')->name)
                        ->disabled((
                                (
                                    (($this->query->get('printer')->inventory_number ?? false) 
                                    ?  !Auth::user()->hasAccess('platform.printer.editWithout') //editar
                                    : true //nuevo)
                                    )
                                )
                                || Auth::user()->hasAnyAccess(['platform.printer.edit', 'systems.admin'])
                            )
                            ? false : true)
                        ->help('Usuario propietario del equipo'),

                        //si la impresora tiene numero de inventario de lo contrario false
                        //si el usuario tiene acceso a editar sin dueño
                        //si el usuario tiene acceso a editar todo o es admin

                    Input::make('_department')
                        ->title('Departamento')
                        ->value(!$this->query->has('userOwner') ? '' : $this->query->get('userOwner')->department->department_name ?? 'Sin departamento')
                        ->readonly(),

                ]),
            ];
    }
}
