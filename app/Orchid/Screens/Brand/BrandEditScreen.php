<?php

namespace App\Orchid\Screens\Brand;

use App\Models\Brand;
use App\Orchid\Layouts\Brand\BrandEditRowsLayout;
use App\Providers\LogService;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class BrandEditScreen extends Screen
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
        return $this->brand->exists ? 'Editar Marca' : 'Crear Marca';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        if(request()->segment(2) != null){
            return [
                'platform.brand.edit',
                'systems.admin'
            ];
        }
        return [
            'platform.brand.create',
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
            BrandEditRowsLayout::class,
        ];
    }

    /**
     * @param Brand $brand
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Brand $brand, Request $request)
    {
        $newBrand =$request->validate([
            'brand_name' => 'required|max:100|min:2',
            'description' => 'required|max:200|min:2',
        ],[
            'brand_name.required' => 'El nombre no puede estar vacío',
            'brand_name.max' => 'El nombre no puede tener más de 100 caracteres',
            'brand_name.min' => 'El nombre no puede tener menos de 2 caracteres',
            'description.required' => 'La descripción no puede estar vacía',
            'description.max' => 'La descripción no puede tener más de 200 caracteres',
            'description.min' => 'La descripción no puede tener menos de 2 caracteres',
        ]);

        $logService = new LogService();
        if($brand->exists){
            $brand->update($newBrand);
            $logService->logAction($request->user(), 'PUT', 'Se ha actualizado la marca a ' . $brand->brand_name);
            Alert::info('Has actualizado la marca');
        }else{
            $brand->create($newBrand);
            $logService->logAction($request->user(), 'POST', 'Se ha creado la marca ' . $brand->brand_name);
            Alert::info('Has creado la marca');
        }

        return redirect()->route('platform.brand.list');
    }
}
