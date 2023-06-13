<?php

namespace App\Orchid\Screens\Brand;

use App\Models\Brand;
use App\Orchid\Layouts\Brand\BrandListTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class BrandListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'brands' => Brand::filters()->defaultSort('id')->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Marcas';
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
        return [
            Link::make('Crear Marca')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.brand.create', 'systems.admin']))
                ->route('platform.brand.edit', ['brand' => new Brand()]),
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
            BrandListTableLayout::class,
        ];
    }

    /**
     * @param Brand $brand
     *
     * @throws \Exception
     */
    public function delete(Brand $brand)
    {
        $brand->delete();
        $logAction = new LogService();
        $logAction->logAction(Auth::user(),'DELETe', 'Eliminó la marca ' . $brand->name);
        Alert::info('La marca ha sido eliminada');
    }
}
