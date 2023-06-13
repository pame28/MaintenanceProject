<?php

namespace App\Orchid\Layouts\Cpu;

use App\Models\Cpu;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use PhpParser\Node\Stmt\Foreach_;

class CpuLisTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'cpus';

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
                ->render(function (Cpu $cpu) {
                    return Link::make($cpu->id)
                        ->route('platform.cpu.card', $cpu);
                })
                ->filter(Input::make()),
            TD::make('inventory_number', 'Número de inventario')
                ->sort()
                ->width('110px')
                ->filter(Input::make()),
            TD::make('brand_name', 'Marca')
                ->sort()
                ->filter(Input::make()),
            TD::make('model', 'Modelo')
                ->sort()
                ->filter(Input::make()),
            TD::make("name", 'Usuario')
                ->sort()
                ->filter(Input::make()),
            TD::make('department_name', 'Departamento')
                ->sort()
                ->filter(Input::make()),
            TD::make('ram', 'RAM')
                ->sort()
                ->filter(Select::make()
                    ->options(
                        function () {
                            $options = [];
                            $options[''] = 'Selecciona';
                            foreach (Cpu::select('ram')->distinct()->get() as $cpu) {
                                $options[$cpu->ram] = $cpu->ram;
                            }
                            return $options;
                        }
                    )
                ),
            TD::make('cpu_status', 'Estado')
                ->sort()
                ->filter(Input::make()),
            TD::make('last_revised_date', 'Última revisión')
                ->sort()
                ->filter(DateTimer::make()->format('Y-m-d')),
            TD::make('last_revised_user_id', 'Revisado por')
                ->sort()
                ->render(function (Cpu $cpu) {
                    return User::find($cpu->last_revised_user_id)->name ?? 'N/A';
                })
                ->filter(TD::FILTER_SELECT, User::all()->pluck('name', 'id')),
            TD::make('acciones', 'Acciones')
                ->render(fn (Cpu $cpu) => DropDown::make()
                    ->align(TD::ALIGN_CENTER)
                    ->icon('options-vertical')
                    ->list([
                        Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.cpu.card', $cpu),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.cpu.edit', 'platform.printer.editWithout', 'systems.admin']))
                            ->route('platform.cpu.edit', $cpu),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['cpu' => $cpu->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.cpu.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar este CPU?'),
                    ])),
        ];
    }
}
