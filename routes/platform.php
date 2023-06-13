<?php

declare(strict_types=1);

use App\Http\Controllers\ReportController;
use App\Models\Department;
use App\Models\Equipment_model;
use App\Orchid\Screens\Brand\BrandCardScreen;
use App\Orchid\Screens\Brand\BrandEditScreen;
use App\Orchid\Screens\Brand\BrandListScreen;
use App\Orchid\Screens\Cpu\CpuCardScreen;
use App\Orchid\Screens\Cpu\CpuEditScreen;
use App\Orchid\Screens\Cpu\CpuListScreen;
use App\Orchid\Screens\Department\DepartmentCardScreen;
use App\Orchid\Screens\Department\DepartmentEditScreeen;
use App\Orchid\Screens\Department\DepartmentListScreeen;
use App\Orchid\Screens\Equipment_model\Equipment_modelCardScreen;
use App\Orchid\Screens\Equipment_model\EquipmentModelEditScreen;
use App\Orchid\Screens\Equipment_model\EquipmentModelListScreen;
use App\Orchid\Screens\Examples\ExampleCardsScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;
use App\Orchid\Screens\Examples\ExampleFieldsAdvancedScreen;
use App\Orchid\Screens\Examples\ExampleFieldsScreen;
use App\Orchid\Screens\Examples\ExampleLayoutsScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\Examples\ExampleTextEditorsScreen;
use App\Orchid\Screens\Maintenance\MaintenanceCardScreen;
use App\Orchid\Screens\Maintenance\MaintenanceEditCpuScreen;
use App\Orchid\Screens\Maintenance\MaintenanceEditScreen;
use App\Orchid\Screens\Maintenance\MaintenanceListScreen;
use App\Orchid\Screens\Maintenance_type\Maintenance_typeCardScreen;
use App\Orchid\Screens\Maintenance_type\MaintenanceTypeEditScreen;
use App\Orchid\Screens\Maintenance_type\MaintenanceTypeListScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Printer\PrinterCardScreen;
use App\Orchid\Screens\Printer\PrinterEditScreen;
use App\Orchid\Screens\Printer\PrinterListScreen;
use App\Orchid\Screens\Report\ReportListScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');
    //->middleware('verified');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example screen'));

Route::screen('example-fields', ExampleFieldsScreen::class)->name('platform.example.fields');
Route::screen('example-layouts', ExampleLayoutsScreen::class)->name('platform.example.layouts');
Route::screen('example-charts', ExampleChartsScreen::class)->name('platform.example.charts');
Route::screen('example-editors', ExampleTextEditorsScreen::class)->name('platform.example.editors');
Route::screen('example-cards', ExampleCardsScreen::class)->name('platform.example.cards');
Route::screen('example-advanced', ExampleFieldsAdvancedScreen::class)->name('platform.example.advanced');

//Route::screen('idea', Idea::class, 'platform.screens.idea');

// Platform > Department > List
Route::screen('departamentos', DepartmentListScreeen::class)
    ->name('platform.department.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Departamentos'), route('platform.department.list')));

// Platform > Department > Department
Route::screen('departamento/{department?}', DepartmentEditScreeen::class)
    ->name('platform.department.edit')
    ->breadcrumbs(fn (Trail $trail, $department = null) => $trail
        ->parent('platform.department.list')
        ->push($department ? $department->department_name : 'Nuevo', route('platform.department.edit', $department)));

// Platform > Department > Card
Route::screen('departamento/{department?}/card', DepartmentCardScreen::class)
    ->name('platform.department.card')
    ->breadcrumbs(fn (Trail $trail, $department) => $trail
        ->parent('platform.department.list')
        ->push($department->department_name, route('platform.department.edit', $department))
        ->push(__('Card')));

// Platform > Brand > List
Route::screen('marcas', BrandListScreen::class)
    ->name('platform.brand.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Marcas'), route('platform.brand.list')));

// Platform > Brand > Brand
Route::screen('marca/{brand?}', BrandEditScreen::class)
    ->name('platform.brand.edit')
    ->breadcrumbs(fn (Trail $trail, $brand = null) => $trail
        ->parent('platform.brand.list')
        ->push($brand ? $brand->brand_name : 'Nueva', route('platform.brand.edit', $brand)));

// Platform > Brand > Card
Route::screen('marca/{brand?}/card', BrandCardScreen::class)
    ->name('platform.brand.card')
    ->breadcrumbs(fn (Trail $trail, $brand) => $trail
        ->parent('platform.brand.list')
        ->push($brand->brand_name, route('platform.brand.edit', $brand))
        ->push(__('Card')));

// Platform > Equipment_model > List
Route::screen('modelos', EquipmentModelListScreen::class)
    ->name('platform.model.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Modelos'), route('platform.model.list')));

// Platform > Equipment_model > Equipment_model
Route::screen('modelo/{modelo?}', EquipmentModelEditScreen::class)
    ->name('platform.model.edit')
    ->breadcrumbs(fn (Trail $trail, $modelo = null) => $trail
        ->parent('platform.model.list')
        ->push($modelo ? $modelo->model : 'Nuevo', route('platform.model.edit', $modelo)));

// Platform > Equipment_model > Card
Route::screen('modelo/{modelo?}/card', Equipment_modelCardScreen::class)
    ->name('platform.model.card')
    ->breadcrumbs(fn (Trail $trail, $modelo) => $trail
        ->parent('platform.model.list')
        ->push(Equipment_model::find($modelo)->model, route('platform.model.edit', $modelo))
        ->push(__('Card')));

// Platform > Maintenance_type > List
Route::screen('tipos-mantenimiento', MaintenanceTypeListScreen::class)
    ->name('platform.maintenance_type.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Tipos de mantenimiento'), route('platform.maintenance_type.list')));

// Platform > Maintenance_type > Maintenance_type
Route::screen('tipo-mantenimiento/{maintenance_type?}', MaintenanceTypeEditScreen::class)
    ->name('platform.maintenance_type.edit')
    ->breadcrumbs(fn (Trail $trail, $maintenance_type = null) => $trail
        ->parent('platform.maintenance_type.list')
        ->push($maintenance_type ? $maintenance_type->type : 'Nuevo', route('platform.maintenance_type.edit', $maintenance_type)));

// Platform > Maintenance_type > Card
Route::screen('tipo-mantenimiento/{maintenance_type?}/card', Maintenance_typeCardScreen::class)
    ->name('platform.maintenance_type.card')
    ->breadcrumbs(fn (Trail $trail, $maintenance_type) => $trail
        ->parent('platform.maintenance_type.list')
        ->push($maintenance_type->type, route('platform.maintenance_type.edit', $maintenance_type))
        ->push(__('Card')));


// Platform > Cpu > List
Route::screen('cpus', CpuListScreen::class)
    ->name('platform.cpu.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('CPU'), route('platform.cpu.list')));

// Platform > Cpu > Cpu
Route::screen('cpu/{cpu?}', CpuEditScreen::class)
    ->name('platform.cpu.edit')
    ->breadcrumbs(fn (Trail $trail, $cpu = null) => $trail
        ->parent('platform.cpu.list')
        ->push($cpu ? $cpu->inventory_number : 'Nuevo', route('platform.cpu.edit', $cpu)));

// Platform > Cpu > Card
Route::screen('cpu/{cpu?}/card', CpuCardScreen::class)
    ->name('platform.cpu.card')
    ->breadcrumbs(fn (Trail $trail, $cpu) => $trail
        ->parent('platform.cpu.list')
        ->push($cpu->inventory_number, route('platform.cpu.edit', $cpu))
        ->push(__('Card')));

// Platform > Impresora > List
Route::screen('impresoras', PrinterListScreen::class)
    ->name('platform.printer.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Impresoras'), route('platform.printer.list')));

// Platform > Impresora > Impresora
Route::screen('impresora/{printer?}', PrinterEditScreen::class)
    ->name('platform.printer.edit')
    ->breadcrumbs(fn (Trail $trail, $printer = null) => $trail
        ->parent('platform.printer.list')
        ->push($printer ? $printer->inventory_number : 'Nuevo', route('platform.printer.edit', $printer)));

// Platform > Impresora > Card
Route::screen('impresora/{printer?}/card', PrinterCardScreen::class)
    ->name('platform.printer.card')
    ->breadcrumbs(fn (Trail $trail, $printer) => $trail
        ->parent('platform.printer.list')
        ->push($printer->inventory_number, route('platform.printer.edit', $printer))
        ->push(__('Card')));

//Platform > Maintenances > List
Route::screen('mantenimientos', MaintenanceListScreen::class)
    ->name('platform.maintenance.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Mantenimientos'), route('platform.maintenance.list')));

//Platform > Maintenances > Maintenance Printer
Route::screen('mantenimiento-impresora/{maintenance?}', MaintenanceEditScreen::class)
    ->name('platform.maintenancePrinter.edit')
    ->breadcrumbs(fn (Trail $trail, $maintenance = null) => $trail
        ->parent('platform.maintenance.list')
        ->push($maintenance ? $maintenance->id : 'Nuevo', route('platform.maintenancePrinter.edit', $maintenance)));

//Platform > Maintenances > Maintenance CPU
Route::screen('mantenimiento-cpu/{maintenance?}', MaintenanceEditCpuScreen::class)
    ->name('platform.maintenanceCpu.edit')
    ->breadcrumbs(fn (Trail $trail, $maintenance = null) => $trail
        ->parent('platform.maintenance.list')
        ->push($maintenance ? $maintenance->id : 'Nuevo', route('platform.maintenanceCpu.edit', $maintenance)));

//Platform > Maintenances > Card
Route::screen('mantenimiento/{maintenance?}/card', MaintenanceCardScreen::class)
    ->name('platform.maintenance.card')
    ->breadcrumbs(fn (Trail $trail, $maintenance) => $trail
        ->parent('platform.maintenance.list')
        ->push($maintenance->id, route('platform.maintenancePrinter.edit', $maintenance))
        ->push(__('Card')));

//Platform > Reports > List
Route::screen('reportes', ReportListScreen::class)
    ->name('platform.report.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Reportes'), route('platform.report.list')));


//Platform > Reports > Report PDF
Route::get('reportOrchid', [ReportController::class, 'generatePDF'])->name('reportOrchid');
