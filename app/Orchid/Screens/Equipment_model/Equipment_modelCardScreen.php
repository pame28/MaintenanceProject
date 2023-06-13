<?php

namespace App\Orchid\Screens\Equipment_model;

use App\Models\Brand;
use App\Models\Equipment_model;
use Orchid\Screen\Screen;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;

class Equipment_modelCardScreen extends Screen
{
    public $equipment_model;
    public $brand;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Equipment_model $equipment_model): iterable
    {
        return [
            'equipment_model' => $equipment_model,
            'brand' => $equipment_model->brand,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Modelo '.$this->equipment_model->model;
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.equipment_model.list',
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
            Layout::legend('equipment_model',[
                Sight::make('id', 'ID'),
                Sight::make('model', 'Modelo'),
                Sight::make('brand.brand_name', 'Marca'),
                Sight::make('created_at', 'Fecha de Creación')
                    ->render(function (Equipment_model $equipment_model) {
                        return $equipment_model->created_at->toDateTimeString();
                    }),
                Sight::make('updated_at', 'Fecha de Actualización')
                    ->render(function (Equipment_model $equipment_model) {
                        return $equipment_model->updated_at->toDateTimeString();
                    }),
            ])->title('Información del Modelo'),

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
