<?php

namespace App\Orchid\Screens\Brand;

use App\Models\Brand;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class BrandCardScreen extends Screen
{
    public $brand;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Brand $brand): iterable
    {
        return [
            'brand' => $brand,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Marca ' . $this->brand->brand_name;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.brand.list',
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
            Layout::legend('brand',[
                Sight::make('id', 'ID'),
                Sight::make('brand_name','Nombre de la Marca'),
                Sight::make('description', 'Descripción'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Brand $brand) {
                        return $brand->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Brand $brand) {
                        return $brand->updated_at->toDateTimeString();
                    }),
            ])->title('Información de la Marca '),
        ];
    }
}
