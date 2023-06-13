<?php

namespace App\Orchid\Layouts\Maintenance;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Listener;

class MaintenanceUserCpuListener extends Listener
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
        'cpu_id',
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
    protected $asyncMethod = 'asyncCpuUser';

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
                        foreach (User::whereNotNull('cpu_id')->get() as $user) {
                            $options[$user->id] = $user->name;
                        }
                        return $options;
                    }

                )
                ->empty($this->query->has('userOwner') ? $this->query->get('userOwner')->name : 'Sin propietario', $this->query->has('userOwner') ? $this->query->get('userOwner')->id : 0)
                ->required()
                ->disabled($this->query->has('userOwner') && Auth::user()->hasAccess('platform.maintenance.editOnly') ? true : false)
                ->help('Usuario propietario del equipo'),


            Input::make('_cpu')
                ->title('CPU')
                ->readonly()
                ->value($this->query->has('userOwner') ? ($this->query->get('maintenance')->cpu->model->brand->brand_name . ' ' . $this->query->get('maintenance')->cpu->model->model . ' / ' . $this->query->get('maintenance')->cpu->inventory_number) : 'Sin CPU')
                ->help('CPU a la que pertenece el mantenimiento'),

            Input::make('cpu_id')
                ->value($this->query->has('userOwner') ? $this->query->get('maintenance')->cpu->id : '')
                ->type('hidden'),

            ]),

        ];
    }
}
