<?php

namespace App\Orchid\Screens\Equipment_model;

use App\Models\Equipment_model;
use App\Orchid\Layouts\Equipment_model\EquipmentModelListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class EquipmentModelListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'models' => Equipment_model::join('brands', 'brands.id', '=', 'equipment_models.brand_id')
                ->select('equipment_models.*', 'brands.brand_name')
                ->filters()
                ->defaultSort('id')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Modelos';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.model.list',
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
        return [
            Link::make('Crear Modelo')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.model.create', 'systems.admin']))
                ->route('platform.model.edit', ['model' => new Equipment_model()]),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            EquipmentModelListTableLayout::class,
        ];
    }

    /**
     * @param Equipment_model $model
     *
     * @throws \Exception
     */
    public function delete(Equipment_model $model)
    {
        $model->delete();
        $logService = new LogService();
        $logService->logAction(auth()->user(), 'DELETe', "Eliminó el modelo $model->model_name");
        Alert::info('El modelo ha sido eliminado');
    }
}
