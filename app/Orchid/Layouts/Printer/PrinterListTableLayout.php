<?php

namespace App\Orchid\Layouts\Printer;

use App\Models\Printer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PrinterListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'printers';

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
                ->render(function (Printer $printer) {
                    return Link::make($printer->id)
                        ->route('platform.printer.card', $printer);
                })
                ->filter(Input::make()),
            TD::make('inventory_number', 'Número de Inventario')
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
            TD::make('printer_status', 'Estado')
                ->sort()
                ->filter(TD::FILTER_SELECT, [
                    'Disponible' => 'Disponible',
                    'Asignado' => 'Asignado',
                    'En mantenimiento' => 'En mantenimiento',
                    'Obsoleto' => 'Obsoleto',
                ]),
            TD::make('last_revised_date', 'Última revisión')
                ->sort()
                ->filter(DateTimer::make()->format('Y-m-d')),
            TD::make('last_revised_user_id','Revisado por')
                ->sort()
                ->render(function (Printer $printer) {
                    return User::find($printer->last_revised_user_id)->name ?? 'N/A';
                })
                ->filter(TD::FILTER_SELECT, User::all()->pluck('name', 'id')),
            TD::make('acciones', 'Acciones')
                ->render(fn (Printer $printer) => DropDown::make()
                    ->icon('options-vertical')
                    ->align(TD::ALIGN_CENTER)
                    ->list([
                        Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.printer.card', $printer),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.printer.edit', 'platform.printer.editWithout', 'systems.admin']))
                            ->route('platform.printer.edit', $printer),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['cpu' => $printer->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.printer.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar esta Impresora?'),
                    ])),

        ];
    }
}
