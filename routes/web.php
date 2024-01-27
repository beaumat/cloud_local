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
use App\Livewire\ShipViaPage\ShipViaForm;
use App\Livewire\ShipViaPage\ShipViaList;
use App\Livewire\StockBinPage\StockBinForm;
use App\Livewire\StockBinPage\StockBinList;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureForm;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureList;

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
                Route::get('/', ItemsList::class)->name('item');
                Route::get('/create', ItemsForm::class)->name('item_create');
                Route::get('/{id}/edit', ItemsForm::class)->name('item_edit');
            });
            Route::prefix('/item-class')->group(function () {
                Route::get('/', ItemClassList::class)->name('item_class');
                Route::get('/create', ItemClassForm::class)->name('item_class_create');
                Route::get('/{id}/edit', ItemClassForm::class)->name('item_class_edit');
            });

            Route::prefix('/item-sub-class')->group(function () {
                Route::get('/', ItemSubClassList::class)->name('item_sub_class');
                Route::get('/create', ItemSubClassForm::class)->name('item_sub_class_create');
                Route::get('/{id}/edit', ItemSubClassForm::class)->name('item_sub_class_edit');
            });

            Route::prefix('/item-group')->group(function () {
                Route::get('/', ItemGroupList::class)->name('item_group');
                Route::get('/create', ItemGroupForm::class)->name('item_group_create');
                Route::get('/{id}/edit', ItemGroupForm::class)->name('item_group_edit');
            });

            Route::prefix('/manufacturers')->group(function () {
                Route::get('/', ManufacturerList::class)->name('manufacturers');
                Route::get('/create', ManufacturerForm::class)->name('manufacturers_create');
                Route::get('/{id}/edit', ManufacturerForm::class)->name('manufacturers_edit');
            });

            Route::prefix('/ship-via')->group(function () {
                Route::get('/', ShipViaList::class)->name('ship_via');
                Route::get('/create', ShipViaForm::class)->name('ship_via_create');
                Route::get('/{id}/edit', ShipViaForm::class)->name('ship_via_edit');
            });

            Route::prefix('/unit-of-measure')->group(function () {
                Route::get('/', UnitOfMeasureList::class)->name('unit_of_measure');
                Route::get('/create', UnitOfMeasureForm::class)->name('unit_of_measure_create');
                Route::get('/{id}/edit', UnitOfMeasureForm::class)->name('unit_of_measure_edit');
            });

            Route::prefix('/inventory-adjustment-type')->group(function () {
                Route::get('/', InventoryAdjustmentTypeList::class)->name('inventory_adjustment_type');
                Route::get('/create', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_create');
                Route::get('/{id}/edit', InventoryAdjustmentTypeForm::class)->name('inventory_adjustment_type_edit');
            });

            Route::prefix('/stock-bin')->group(function () {
                Route::get('/', StockBinList::class)->name('stock_bin');
                Route::get('/create', StockBinForm::class)->name('stock_bin_create');
                Route::get('/{id}/edit', StockBinForm::class)->name('stock_bin_edit');
            });

            Route::prefix('/price-level')->group(function () {
                Route::get('/', PriceLevelList::class)->name('price_level');
                Route::get('/create', PriceLevelForm::class)->name('price_level_create');
                Route::get('/{id}/edit', PriceLevelForm::class)->name('price_level_edit');
            });
        });
    });
});

require __DIR__ . '/auth.php';
