<?php

namespace App\Orchid\Screens\Cpu;

use App\Models\Cpu;
use App\Orchid\Layouts\Cpu\CpuLisTableLayout;
use App\Providers\LogService;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class CpuListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'cpus' => Cpu::select('cpus.*', 'equipment_models.model', 'brands.brand_name', 'users.name', 'departments.department_name')
                ->join('equipment_models', 'cpus.model_id', '=', 'equipment_models.id')
                ->join('brands', 'equipment_models.brand_id', '=', 'brands.id')
                ->leftJoin('users', 'users.cpu_id', '=', 'cpus.id')
                ->leftJoin('departments', 'departments.id', '=', 'users.department_id')
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
        return 'CPUs';
    }

    /**
     * Permission
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.cpu.list',
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
            Link::make('Crear CPU')
                ->icon('plus')
                ->canSee(Auth::user()->hasAnyAccess(['platform.cpu.create', 'systems.admin']))
                ->route('platform.cpu.edit', ['cpu' => new Cpu()]),
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
            CpuLisTableLayout::class,
        ];
    }

    /**
     * @param Cpu $cpu
     */
    public function delete(Cpu $cpu)
    {
        $userOwnerExist = $cpu->userOwner()->exists();
        if($userOwnerExist){
            $cpu->userOwner()->update(['cpu_id' => null]);
        }

        $cpu->delete();
        $logAction = new LogService();
        $logAction->logAction(Auth::user(), 'DELETe', 'Se eliminó el CPU con ID: ' . $cpu->id);

        Alert::info('Ha eliminado el CPU');
    }
}
