<?php

use App\Livewire\DashboardPage\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\InventoryAdjustmentTypePage\InventoryAdjustmentTypeForm;
use App\Livewire\InventoryAdjustmentTypePage\InventoryAdjustmentTypeList;
use App\Livewire\ItemClassPage\ItemClassForm;
use App\Livewire\ItemClassPage\ItemClassList;
use App\Livewire\ItemGroupPage\ItemGroupForm;
use App\Livewire\ItemGroupPage\ItemGroupList;
use App\Livewire\ItemPage\ItemsForm;
use App\Livewire\ItemPage\ItemsList;
use App\Livewire\ItemSubClassPage\ItemSubClassForm;
use App\Livewire\ItemSubClassPage\ItemSubClassList;
use App\Livewire\ManufacturerPage\ManufacturerForm;
use App\Livewire\ManufacturerPage\ManufacturerList;
use App\Livewire\PriceLevelPage\PriceLevelForm;
use App\Livewire\PriceLevelPage\PriceLevelList;
use App\Livewire\RolePermissionPage\RolePermissionConfig;
use App\Livewire\RolePermissionPage\RolePermissionList;
use App\Livewire\ShipViaPage\ShipViaForm;
use App\Livewire\ShipViaPage\ShipViaList;
use App\Livewire\StockBinPage\StockBinForm;
use App\Livewire\StockBinPage\StockBinList;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureForm;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureList;
use App\Livewire\User\UserForm;
use App\Livewire\User\UserList;
use App\Livewire\User\UserRoles;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    if (auth()->check()) {

        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});


Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');


    Route::prefix('/maintenance')->name('maintenance')->group(function () {

        Route::prefix('/inventory')->name('inventory')->group(function () {

            Route::prefix('/items')->group(function () {
                Route::get('/', ItemsList::class)->name('item')->middleware(['permission:items.view']);
                Route::get('/create', ItemsForm::class)->name('item_create')->middleware(['permission:items.create']);
                Route::get('/{id}/edit', ItemsForm::class)->name('item_edit')->middleware(['permission:items.edit']);
            });
            Route::prefix('/item-class')->group(function () {
                Route::get('/', ItemClassList::class)->name('item_class')->middleware(['permission:item-class.view']);
                Route::get('/create', ItemClassForm::class)->name('item_class_create')->middleware(['permission:item-class.create']);
                Route::get('/{id}/edit', ItemClassForm::class)->name('item_class_edit')->middleware(['permission:item-class.edit']);
            });

            Route::prefix('/item-sub-class')->group(function () {
                Route::get('/', ItemSubClassList::class)->name('item_sub_class')->middleware(['permission:item-sub-class.view']);
                Route::get('/create', ItemSubClassForm::class)->name('item_sub_class_create')->middleware(['permission:item-sub-class.create']);
                Route::get('/{id}/edit', ItemSubClassForm::class)->name('item_sub_class_edit')->middleware(['permission:item-sub-class.edit']);
            });

            Route::prefix('/item-group')->group(function () {
                Route::get('/', ItemGroupList::class)->name('item_group')->middleware(['permission:item-group.view']);
                Route::get('/create', ItemGroupForm::class)->name('item_group_create')->middleware(['permission:item-group.create']);
                Route::get('/{id}/edit', ItemGroupForm::class)->name('item_group_edit')->middleware(['permission:item-group.edit']);
            });

            Route::prefix('/manufacturers')->group(function () {
                Route::get('/', ManufacturerList::class)->name('manufacturers')->middleware(['permission:manufacturer.view']);
                Route::get('/create', ManufacturerForm::class)->name('manufacturers_create')->middleware(['permission:manufacturer.create']);
                Route::get('/{id}/edit', ManufacturerForm::class)->name('manufacturers_edit')->middleware(['permission:manufacturer.edit']);
            });

            Route::prefix('/ship-via')->group(function () {
                Route::get('/', ShipViaList::class)->name('ship_via')->middleware(['permission:ship-via.view']);
                Route::get('/create', ShipViaForm::class)->name('ship_via_create')->middleware(['permission:ship-via.create']);
                Route::get('/{id}/edit', ShipViaForm::class)->name('ship_via_edit')->middleware(['permission:ship-via.edit']);
            });

            Route::prefix('/unit-of-measure')->group(function () {
                Route::get('/', UnitOfMeasureList::class)->name('unit_of_measure')->middleware(['permission:unit-of-measure.view']);
                Route::get('/create', UnitOfMeasureForm::class)->name('unit_of_measure_create')->middleware(['permission:unit-of-measure.create']);
                Route::get('/{id}/edit', UnitOfMeasureForm::class)->name('unit_of_measure_edit')->middleware(['permission:unit-of-measure.edit']);
            });

            Route::prefix('/inventory-adjustment-type')->group(function () {
                Route::get('/', InventoryAdjustmentTypeList::class)->name('inventory_adjustment_type')->middleware(['permission:inventory-adjustment-type.view']);
                Route::get('/create', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_create')->middleware(['permission:inventory-adjustment-type.create']);
                Route::get('/{id}/edit', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_edit')->middleware(['permission:inventory-adjustment-type.edit']);
            });

            Route::prefix('/stock-bin')->group(function () {
                Route::get('/', StockBinList::class)->name('stock_bin')->middleware(['permission:stock-bin.view']);
                Route::get('/create', StockBinForm::class)->name('stock_bin_create')->middleware(['permission:stock-bin.create']);
                Route::get('/{id}/edit', StockBinForm::class)->name('stock_bin_edit')->middleware(['permission:stock-bin.edit']);
            });

            Route::prefix('/price-level')->group(function () {
                Route::get('/', PriceLevelList::class)->name('price_level')->middleware(['permission:price-level.view']);
                Route::get('/create', PriceLevelForm::class)->name('price_level_create')->middleware(['permission:price-level.create']);
                Route::get('/{id}/edit', PriceLevelForm::class)->name('price_level_edit')->middleware(['permission:price-level.edit']);
            });
        });

        Route::prefix('/settings')->name('settings')->group(function () {
            Route::prefix('/user')->middleware(['permission:users'])->group(function () {
                Route::get('/', UserList::class)->name('users');
                Route::get('/create', UserForm::class)->name('users_create');
                Route::get('/{id}/edit', UserForm::class)->name('users_edit');
                Route::get('/{id}/role', UserRoles::class)->name('users_role');
            });

            Route::prefix('/rolespermission')->middleware(['permission:roles-and-permission'])->group(function () {
                Route::get('/', RolePermissionList::class)->name('roles');
                Route::get('/{id}/permission', RolePermissionConfig::class)->name('roles_permission');
                // Route::get('/{id}/edit', UserForm::class)->name('users_edit');
            });
        });
    });
});

require __DIR__ . '/auth.php';
