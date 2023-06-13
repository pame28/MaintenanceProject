<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Department;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('name', __('Name'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (User $user) => new Persona($user->presenter())),

            TD::make('email', __('Email'))
                ->sort()
                ->cantHide()
                ->filter(Input::make())
                ->render(fn (User $user) => ModalToggle::make($user->email)
                    ->modal('asyncEditUserModal')
                    ->modalTitle($user->presenter()->title())
                    ->method('saveUser')
                    ->asyncParameters([
                        'user' => $user->id,
                    ])),

            TD::make('department_name', __('Department'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_SELECT, Department::pluck('department_name', 'department_name')
                    /* Select::make()
                    ->options(
                        function(){
                            $departments = \App\Models\Department::all();
                            $options = [];
                            $options[0] = 'Todos';
                            foreach($departments as $department){
                                $options[$department->id] = $department->department_name;
                            }
                            return $options;
                        }
                    ) */
                )
                ->render(fn (User $user) => $user->department->department_name ?? 'Sin departamento'),


            TD::make('expiration_date', __('Status'))
                ->sort()
                ->filter(DateTimer::make()
                    ->title('Fecha de expiración')
                    ->placeholder('Fecha de expiración')
                    ->format('Y-m-d')
                    ->popover('Fecha de expiración')
                )
                ->render(fn (User $user) => ModalToggle::make($user->expiration_date >= now() ? ('Activo' . '/' . $user->expiration_date) : ('Inactivo' . '/' . $user->expiration_date))
                    ->modal('changeStatusUserModal')
                    ->modalTitle('Cambiar estado del usuario ' . $user->presenter()->title())
                    ->method('changeStatus')
                    ->asyncParameters([
                        'user' => $user->id,
                    ]
                )),

            TD::make('updated_at', __('Last edit'))
                ->sort()
                ->render(fn (User $user) => $user->updated_at->toDateTimeString()),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (User $user) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        Link::make(__('Edit'))
                            ->route('platform.systems.users.edit', $user->id)
                            ->icon('pencil'),

                        Button::make(__('Delete'))
                            ->icon('trash')
                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $user->id,
                            ]),
                    ])),
        ];
    }
}
