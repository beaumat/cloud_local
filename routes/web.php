<?php

use App\Livewire\BillCredit\BillCreditForm;
use App\Livewire\BillCredit\BillCreditList;
use App\Livewire\BillPayments\BillPaymentForm;
use App\Livewire\BillPayments\BillPaymentList;
use App\Livewire\Bills\BillingForm;
use App\Livewire\Bills\BillingList;
use App\Livewire\BuildAssembly\BuildAssemblyForm;
use App\Livewire\BuildAssembly\BuildAssemblyList;
use App\Livewire\ChartOfAccount\ChartOfAccountForm;
use App\Livewire\ChartOfAccount\ChartOfAccountList;
use App\Livewire\CreditMemo\CreditMemoForm;
use App\Livewire\CreditMemo\CreditMemoList;
use App\Livewire\Customer\CustomerForm;
use App\Livewire\Customer\CustomerList;
use App\Livewire\DashboardPage\Dashboard;
use App\Livewire\Doctor\DoctorForm;
use App\Livewire\Doctor\DoctorList;
use App\Livewire\Employees\EmployeeForm;
use App\Livewire\Employees\EmployeeList;
use App\Livewire\GeneralJournal\GeneralJournalForm;
use App\Livewire\GeneralJournal\GeneralJournalList;
use App\Livewire\Hemodialysis\HemoForm;
use App\Livewire\Hemodialysis\HemoList;
use App\Livewire\Hemodialysis\PrintForm;
use App\Livewire\HemodialysisMachine\HemoMachineForm;
use App\Livewire\HemodialysisMachine\HemoMachineList;
use App\Livewire\InventoryAdjustment\InventoryAdjustmentForm;
use App\Livewire\InventoryAdjustment\InventoryAdjustmentList;
use App\Livewire\Invoice\InvoiceForm;
use App\Livewire\Invoice\InvoiceList;
use App\Livewire\Option\OptionSettings;
use App\Livewire\PatientPayment\PatientPaymentForm;
use App\Livewire\PatientPayment\PatientPaymentList;
use App\Livewire\Payment\PaymentForm;
use App\Livewire\Payment\PaymentList;
use App\Livewire\PhilHealth\PhilHealthForm;
use App\Livewire\PhilHealth\PhilHealthList;
use App\Livewire\PhilHealth\PhilHealthPrint;
use App\Livewire\PhilHealth\PhilHealthPrintForm;
use App\Livewire\Requirement\RequirementForm;
use App\Livewire\Requirement\RequirementList;
use App\Livewire\SalesOrder\SalesOrderForm;
use App\Livewire\SalesOrder\SalesOrderList;
use App\Livewire\Scheduler\PrintSchedulesPrintOut;
use App\Livewire\Scheduler\SchedulerForm;
use App\Livewire\Scheduler\SchedulerList;
use App\Livewire\ServiceCharge\ServiceChargeForm;
use App\Livewire\ServiceCharge\ServiceChargeList;
use App\Livewire\Shift\ShiftForm;
use App\Livewire\Shift\ShiftList;
use App\Livewire\Statement\Statement;
use App\Livewire\StatementOfAccount\SoaModule;
use App\Livewire\StockTransfer\StockTransferForm;
use App\Livewire\StockTransfer\StockTransferList;
use App\Livewire\TaxCredit\TaxCreditList;
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
use App\Livewire\Location\LocationForm;
use App\Livewire\Location\LocationList;
use App\Livewire\LocationGroup\LocationGroupForm;
use App\Livewire\LocationGroup\LocationGroupList;
use App\Livewire\ManufacturerPage\ManufacturerForm;
use App\Livewire\ManufacturerPage\ManufacturerList;
use App\Livewire\Patient\PatientForm;
use App\Livewire\Patient\PatientList;
use App\Livewire\PaymentMethod\PaymentMethodForm;
use App\Livewire\PaymentMethod\PaymentMethodList;
use App\Livewire\PaymentTerm\PaymentTermForm;
use App\Livewire\PaymentTerm\PaymentTermList;
use App\Livewire\PriceLevelPage\PriceLevelForm;
use App\Livewire\PriceLevelPage\PriceLevelList;
use App\Livewire\PurchaseOrder\PurchaseOrderForm;
use App\Livewire\PurchaseOrder\PurchaseOrderList;
use App\Livewire\RolePermissionPage\RolePermissionConfig;
use App\Livewire\RolePermissionPage\RolePermissionList;
use App\Livewire\ShipViaPage\ShipViaForm;
use App\Livewire\ShipViaPage\ShipViaList;
use App\Livewire\StockBinPage\StockBinForm;
use App\Livewire\StockBinPage\StockBinList;
use App\Livewire\Tax\TaxForm;
use App\Livewire\Tax\TaxList;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureForm;
use App\Livewire\UnitOfMeasurePage\UnitOfMeasureList;
use App\Livewire\User\UserForm;
use App\Livewire\User\UserList;
use App\Livewire\User\UserRoles;
use App\Livewire\Vendor\VendorForm;
use App\Livewire\Vendor\VendorList;

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

    Route::prefix('/patients')->name('patients')->group(function () {
        Route::prefix('/schedules')->group(function () {
            Route::get('/', SchedulerList::class)->name('schedules');
            Route::get('/setup', SchedulerForm::class)->name('schedules_setup');
            Route::get('/{year}/{month}/{week}/{location}/{shift}/print-form', PrintSchedulesPrintOut::class)->name('schedules_print');
        });

        Route::prefix('/service-charges')->group(function () {
            Route::get('/', ServiceChargeList::class)->name('service_charges');
            Route::get('/create', ServiceChargeForm::class)->name('service_charges_create');
            Route::get('/{id}/edit', ServiceChargeForm::class)->name('service_charges_edit');
        });

        Route::prefix('/hemodialysis-treatment')->group(function () {
            Route::get('/', HemoList::class)->name('hemo');
            Route::get('/create', HemoForm::class)->name('hemo_create');
            Route::get('/{id}/edit', HemoForm::class)->name('hemo_edit');
            Route::get('/{id}/print', PrintForm::class)->name('hemo_print');
        });

        Route::prefix('/payments')->group(function () {
            Route::get('/', PatientPaymentList::class)->name('payment');
            Route::get('/create', PatientPaymentForm::class)->name('payment_create');
            Route::get('/{id}/edit', PatientPaymentForm::class)->name('payment_edit');
        });

        Route::prefix('/phil-health')->group(function () {
            Route::get('/', PhilHealthList::class)->name('phic');
            Route::get('/create', PhilHealthForm::class)->name('phic_create');
            Route::get('/{id}/edit', PhilHealthForm::class)->name('phic_edit');
            Route::get('/{id}/print', PhilHealthPrint::class)->name('phic_print');
            Route::get('/{id}/print-form', PhilHealthPrintForm::class)->name('phic_print_form');
        });

        Route::prefix('/statement-of-account')->group(function () {
            Route::get('/', SoaModule::class)->name('soa');
        });
    });

    Route::prefix('/customers')->name('customers')->group(function () {
        Route::prefix('/sales-order')->group(function () {
            Route::get('/', SalesOrderList::class)->name('sales_order');
            Route::get('/create', SalesOrderForm::class)->name('sales_order_create');
            Route::get('/{id}/edit', SalesOrderForm::class)->name('sales_order_edit');
        });
        Route::prefix('/invoice')->group(function () {
            Route::get('/', InvoiceList::class)->name('invoice');
            Route::get('/create', InvoiceForm::class)->name('invoice_create');
            Route::get('/{id}/edit', InvoiceForm::class)->name('invoice_edit');
        });
        Route::prefix('/payment')->group(function () {
            Route::get('/', PaymentList::class)->name('payment');
            Route::get('/create', PaymentForm::class)->name('payment_create');
            Route::get('/{id}/edit', PaymentForm::class)->name('payment_edit');
        });
        Route::prefix('/statement')->group(function () {
            Route::get('/', Statement::class)->name('statement');
        });
        Route::prefix('/credit-memo')->group(function () {
            Route::get('/', CreditMemoList::class)->name('credit_memo');
            Route::get('/create', CreditMemoForm::class)->name('credit_memo_create');
            Route::get('/{id}/edit', CreditMemoForm::class)->name('credit_memo_edit');
        });
        Route::prefix('/tax-credit')->group(function () {
            Route::get('/', TaxCreditList::class)->name('tax_credit');
        });

    });

    Route::prefix('/vendors')->name('vendors')->group(function () {
        Route::prefix('/purchase-order')->group(function () {
            Route::get('/', PurchaseOrderList::class)->name('purchase_order');
            Route::get('/create', PurchaseOrderForm::class)->name('purchase_order_create');
            Route::get('/{id}/edit', PurchaseOrderForm::class)->name('purchase_order_edit');
        });

        Route::prefix('/bills')->group(function () {
            Route::get('/', BillingList::class)->name('bills');
            Route::get('/create', BillingForm::class)->name('bills_create');
            Route::get('/{id}/edit', BillingForm::class)->name('bills_edit');
        });


        Route::prefix('/bill-payments')->group(function () {
            Route::get('/', BillPaymentList::class)->name('bill_payment');
            Route::get('/create', BillPaymentForm::class)->name('bill_payment_create');
            Route::get('/{id}/edit', BillPaymentForm::class)->name('bill_payment_edit');
        });

        Route::prefix('/bill-credits')->group(function () {
            Route::get('/', BillCreditList::class)->name('bill_credit');
            Route::get('/create', BillCreditForm::class)->name('bill_credit_create');
            Route::get('/{id}/edit', BillCreditForm::class)->name('bill_credit_edit');
        });

        Route::prefix('/withholding-tax')->group(function () {
        });
    });

    Route::prefix('/company')->name('company')->group(function () {
        Route::prefix('/build-assembly')->group(function () {
            Route::get('/', BuildAssemblyList::class)->name('build_assembly');
            Route::get('/create', BuildAssemblyForm::class)->name('build_assembly_create');
            Route::get('/{id}/edit', BuildAssemblyForm::class)->name('build_assembly_edit');
        });

        Route::prefix('/general-journal')->group(function () {
            Route::get('/', GeneralJournalList::class)->name('general_journal');
            Route::get('/create', GeneralJournalForm::class)->name('general_journal_create');
            Route::get('/{id}/edit', GeneralJournalForm::class)->name('general_journal_edit');
        });

        Route::prefix('/inventory-adjustment')->group(function () {
            Route::get('/', InventoryAdjustmentList::class)->name('inventory_adjustment');
            Route::get('/create', InventoryAdjustmentForm::class)->name('inventory_adjustment_create');
            Route::get('/{id}/edit', InventoryAdjustmentForm::class)->name('inventory_adjustment_edit');
        });

        Route::prefix('/stock-transfer')->group(function () {
            Route::get('/', StockTransferList::class)->name('stock_transfer');
            Route::get('/create', StockTransferForm::class)->name('stock_transfer_create');
            Route::get('/{id}/edit', StockTransferForm::class)->name('stock_transfer_edit');
        });
    });



    Route::prefix('/maintenance')->name('maintenance')->group(function () {

        Route::prefix('/contact')->name('contact')->group(function () {

            Route::prefix('/customer')->group(function () {
                Route::get('/', CustomerList::class)->name('customer');
                Route::get('/create', CustomerForm::class)->name('customer_create');
                Route::get('/{id}/edit', CustomerForm::class)->name('customer_edit');
            });

            Route::prefix('/vendor')->group(function () {
                Route::get('/', VendorList::class)->name('vendor');
                Route::get('/create', VendorForm::class)->name('vendor_create');
                Route::get('/{id}/edit', VendorForm::class)->name('vendor_edit');
            });

            Route::prefix('/employees')->group(function () {
                Route::get('/', EmployeeList::class)->name('employees');
                Route::get('/create', EmployeeForm::class)->name('employees_create');
                Route::get('/{id}/edit', EmployeeForm::class)->name('employees_edit');
            });

            Route::prefix('/patients')->group(function () {
                Route::get('/', PatientList::class)->name('patients');
                Route::get('/create', PatientForm::class)->name('patients_create');
                Route::get('/{id}/edit', PatientForm::class)->name('patients_edit');
            });

            Route::prefix('/doctors')->group(function () {
                Route::get('/', DoctorList::class)->name('doctors');
                Route::get('/create', DoctorForm::class)->name('doctors_create');
                Route::get('/{id}/edit', DoctorForm::class)->name('doctors_edit');
            });
        });

        Route::prefix('/financial')->name('financial')->group(function () {

            Route::prefix('/chart-of-account')->group(function () {
                Route::get('/', ChartOfAccountList::class)->name('coa');
                Route::get('/create', ChartOfAccountForm::class)->name('coa_create');
                Route::get('/{id}/edit', ChartOfAccountForm::class)->name('coa_edit');
            });

            Route::prefix('/payment-method')->group(function () {
                Route::get('/', PaymentMethodList::class)->name('payment_method');
                Route::get('/create', PaymentMethodForm::class)->name('payment_method_create');
                Route::get('/{id}/edit', PaymentMethodForm::class)->name('payment_method_edit');
            });

            Route::prefix('/payment-term')->group(function () {
                Route::get('/', PaymentTermList::class)->name('payment_term');
                Route::get('/create', PaymentTermForm::class)->name('payment_term_create');
                Route::get('/{id}/edit', PaymentTermForm::class)->name('payment_term_edit');
            });

            Route::prefix('/tax-list')->group(function () {
                Route::get('/', TaxList::class)->name('tax_list');
                Route::get('/create', TaxForm::class)->name('tax_list_create');
                Route::get('/{id}/edit', TaxForm::class)->name('tax_list_edit');
            });
        });

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

        Route::prefix('/others')->name('others')->group(function () {
            Route::prefix('/shift')->group(function () {
                Route::get('/', ShiftList::class)->name('shift');
                Route::get('/create', ShiftForm::class)->name('shift_create');
                Route::get('/{id}/edit', ShiftForm::class)->name('shift_edit');
            });

            Route::prefix('/hemodialysis-machine')->group(function () {
                Route::get('/', HemoMachineList::class)->name('hemo_machine');
                Route::get('/create', HemoMachineForm::class)->name('hemo_machine_create');
                Route::get('/{id}/edit', HemoMachineForm::class)->name('hemo_machine_edit');
            });

            Route::prefix('/requirement')->group(function () {
                Route::get('/', RequirementList::class)->name('requirement');
                Route::get('/create', RequirementForm::class)->name('requirement_create');
                Route::get('/{id}/edit', RequirementForm::class)->name('requirement_edit');
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
            });

            Route::prefix('/location')->group(function () {
                Route::get('/', LocationList::class)->name('location')->middleware(['permission:location.view']);
                Route::get('/create', LocationForm::class)->name('location_create')->middleware(['permission:location.create']);
                Route::get('/{id}/edit', LocationForm::class)->name('location_edit')->middleware(['permission:location.edit']);
            });
            Route::prefix('/location-group')->group(function () {
                Route::get('/', LocationGroupList::class)->name('location_group')->middleware(['permission:location-group.view']);
                Route::get('/create', LocationGroupForm::class)->name('location_group_create')->middleware(['permission:location-group.create']);
                Route::get('/{id}/edit', LocationGroupForm::class)->name('location_group_edit')->middleware(['permission:location-group.edit']);
            });

            Route::prefix('/option')->group(function () {
                Route::get('/', OptionSettings::class)->name('option');
            });
        });
    });
});

require __DIR__ . '/auth.php';
