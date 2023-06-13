<?php

namespace App\Orchid\Layouts\Cpu;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class CpuUserOwnerListener extends Listener
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
    protected $asyncMethod = 'asyncCpuUserOwner';

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
                            foreach (User::whereNull('cpu_id')->get() as $user) {
                                $options[$user->id] = $user->name;
                            }
                            return $options;
                        }

                    )
                    ->empty(!$this->query->has('userOwner') ? 'Sin propietario' : $this->query->get('userOwner')->name, !$this->query->has('userOwner') ? '0' : $this->query->get('userOwner')->id) //$this->query->get('userOwner')->name)
                    ->disabled((
                        (
                            (($this->query->get('cpu')->inventory_number ?? false)
                            ?  !Auth::user()->hasAccess('platform.cpu.editWithout') //editar
                            : true //nuevo)
                            )
                        )
                        || Auth::user()->hasAnyAccess(['platform.cpu.edit', 'systems.admin'])
                    )
                    ? false : true)
                    ->help('Usuario propietario del equipo'),

                Input::make('_department')
                    ->title('Departamento')
                    ->value(!$this->query->has('userOwner') ? '' : $this->query->get('userOwner')->department->department_name ?? 'Sin departamento')
                    ->readonly(),

            ]),
        ];
    }
}
