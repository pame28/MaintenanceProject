<?php

namespace App\Orchid\Layouts\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BrandListTableLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'brands';

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
                ->render(function (Brand $brand) {
                    return Link::make($brand->id)
                        ->route('platform.brand.card', $brand);
                })
                ->filter(Input::make()),
            TD::make('brand_name', 'Marca')
                ->sort()
                ->filter(Input::make()),
            TD::make('description', 'Descripción')
                ->render(function (Brand $brand) {
                    return substr($brand->description, 0, 50) . '...';
                })
                ->sort(),
            TD::make('acciones', 'Acciones')
                ->align(TD::ALIGN_CENTER)
                ->render( fn (Brand $brand) => DropDown::make()
                    ->icon('options-vertical')
                    ->list([
                        Link::make('Ver')
                            ->icon('eye')
                            ->route('platform.brand.card', $brand ),
                        Link::make('Editar')
                            ->icon('pencil')
                            ->canSee(Auth::user()->hasAnyAccess(['platform.brand.edit', 'systems.admin']))
                            ->route('platform.brand.edit', $brand),
                        Button::make('Eliminar')
                            ->icon('trash')
                            ->method('delete')
                            ->parameters(['brand' => $brand->id])
                            ->canSee(Auth::user()->hasAnyAccess(['platform.brand.delete', 'systems.admin']))
                            ->confirm('¿Estás seguro de querer eliminar esta marca?'),
                    ])),
        ];
    }
}
