<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Marcas')
                ->icon('tag')
                ->route('platform.brand.list')
                ->title('Catálogo')
                ->permission(['platform.brand.list', 'systems.admin']),

            Menu::make('Modelos')
                ->icon('module')
                ->route('platform.model.list')
                ->permission(['platform.model.list', 'systems.admin']),

            Menu::make('CPU')
                ->icon('windows')
                ->route('platform.cpu.list')
                ->title('Equipos')
                ->permission(['platform.cpu.list', 'systems.admin']),

            Menu::make('Impresoras')
                ->icon('printer')
                ->route('platform.printer.list')
                ->permission(['platform.printer.list', 'systems.admin']),

            Menu::make('Tipos de mantenimiento')
                ->icon('wrench')
                ->route('platform.maintenance_type.list')
                ->title('Mantenimientos')
                ->permission(['platform.maintenance_type.list', 'systems.admin']),

            Menu::make('Mantenimientos')
                ->icon('calendar')
                ->route('platform.maintenance.list')
                ->permission(['platform.maintenance.list', 'systems.admin']),

            Menu::make('Reporte')
                ->icon('chart')
                ->route('platform.report.list')
                ->permission(['platform.report.list', 'systems.admin']),

            Menu::make('Departamentos')
                ->icon('building')
                ->route('platform.department.list')
                ->title('Departamentos')
                ->permission(['platform.department.list', 'systems.admin']),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission(['platform.systems.users', 'systems.admin'])
                ->title(__('Control de usuarios')),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission(['platform.systems.roles', 'systems.admin']),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('systems.admin', __('Administrador'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users'))
                ->addPermission('platform.systems.reports', __('Reportes')),

            ItemPermission::group(__('Departamento'))
            ->addPermission('platform.department.list', __('1.Ver'))
            ->addPermission('platform.department.create', __('2.Crear'))
            ->addPermission('platform.department.edit', __('3.Editar'))
            ->addPermission('platform.department.delete', __('4.Eliminar')),

            ItemPermission::group(__('Marca'))
            ->addPermission('platform.brand.list', __('1.Ver'))
            ->addPermission('platform.brand.create', __('2.Crear'))
            ->addPermission('platform.brand.edit', __('3.Editar'))
            ->addPermission('platform.brand.delete', __('4.Eliminar')),

            ItemPermission::group(__('Modelo'))
            ->addPermission('platform.model.list', __('1.Ver'))
            ->addPermission('platform.model.create', __('2.Crear'))
            ->addPermission('platform.model.edit', __('3.Editar'))
            ->addPermission('platform.model.delete', __('4.Eliminar')),

            ItemPermission::group(__('Tipos'))
            ->addPermission('platform.maintenance_type.list', __('1.Ver'))
            ->addPermission('platform.maintenance_type.create', __('2.Crear'))
            ->addPermission('platform.maintenance_type.edit', __('3.Editar'))
            ->addPermission('platform.maintenance_type.delete', __('4.Eliminar')),

            ItemPermission::group(__('CPU'))
            ->addPermission('platform.cpu.list', __('1.Ver'))
            ->addPermission('platform.cpu.create', __('2.Crear'))
            ->addPermission('platform.cpu.edit', __('3.Editar Todo'))
            ->addPermission('platform.cpu.editWithout', __('3.Editar sin propietario'))
            ->addPermission('platform.cpu.delete', __('4.Eliminar')),

            ItemPermission::group(__('Impresora'))
            ->addPermission('platform.printer.list', __('1.Ver'))
            ->addPermission('platform.printer.create', __('2.Crear'))
            ->addPermission('platform.printer.edit', __('3.Editar Todo'))
            ->addPermission('platform.printer.editWithout', __('3.Editar sin propietario'))
            ->addPermission('platform.printer.delete', __('4.Eliminar')),

            ItemPermission::group(__('Mantenimiento'))
            ->addPermission('platform.maintenance.list', __('1.Ver'))
            ->addPermission('platform.maintenance.create', __('2.Crear'))
            ->addPermission('platform.maintenance.edit', __('3.Editar Todo'))
            ->addPermission('platform.maintenance.editOnly', __('3.Editar solo estado'))
            ->addPermission('platform.maintenance.delete', __('5.Eliminar')),
        ];
    }
}
