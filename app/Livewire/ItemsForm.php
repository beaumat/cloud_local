<?php

namespace App\Livewire;

use App\Models\Accounts;
use App\Models\Contacts;
use App\Models\ItemClass;
use App\Models\ItemGroup;
use App\Models\Items;
use App\Models\ItemSubClass;
use App\Models\ItemType;
use App\Models\Manufacturers;
use App\Models\RateType;
use App\Models\StockType;
use App\Models\UnitOfMeasures;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use App\Services\ItemServices;

class ItemsForm extends Component
{

    public int  $ID;
    public string $CODE;
    public string $DESCRIPTION;
    public string $PURCHASE_DESCRIPTION;
    public int  $GROUP_ID;
    public int $SUB_CLASS_ID;
    public int $TYPE = 0;
    public int $STOCK_TYPE;
    public int $GL_ACCOUNT_ID;
    public int $COGS_ACCOUNT_ID;
    public int $ASSET_ACCOUNT_ID;
    public bool $TAXABLE;
    public int $PREFERRED_VENDOR_ID;
    public int $MANUFACTURER_ID;
    public float $RATE;
    public float $COST;
    public int $RATE_TYPE;
    public int $PAYMENT_METHOD_ID;
    public string $NOTES;
    public int $BASE_UNIT_ID;
    public int $PURCHASES_UNIT_ID;
    public int $SHIPPING_UNIT_ID;
    public int $SALES_UNIT_ID;
    public bool $PRINT_INDIVIDUAL_ITEMS;
    public bool $INACTIVE;
    public string $CUSTOM_FIELD1;
    public string $CUSTOM_FIELD2;
    public string $CUSTOM_FIELD3;
    public string $CUSTOM_FIELD4;
    public string $CUSTOM_FIELD5;
    public bool $NON_PORFOLIO_COMPUTATION;
    public bool $BUNDLE_SET;
    public bool $NON_DISCOUNTED_ITEM;
    public string $PIC_FILENAME;
    public bool $IS_EXPIRED;



    public $itemType = [];
    public $stockType = [];
    public $itemGroup = [];

    public $CLASS_ID;
    public $itemClass = [];
    public $itemSubClass = [];

    public $vendors = [];
    public $manufacturers = [];
    public $accounts = [];
    public $rateType = [];
    public $units = [];
    public function LoadDropdown()
    {

        $this->itemSubClass = [];
        $this->itemGroup = ItemGroup::where('ITEM_TYPE', $this->TYPE)->get();
        $this->itemClass = ItemClass::all();
        $this->itemType = ItemType::where('INACTIVE', '0')->get();
        $this->stockType = StockType::all();
        $this->manufacturers = Manufacturers::all();
        $this->vendors = Contacts::query()->select(['ID', 'PRINT_NAME_AS as NAME'])->where('TYPE', '0')->where('INACTIVE', '0')->orderBy('PRINT_NAME_AS', 'asc')->get();
        $this->accounts = Accounts::query()->select(['ID', 'NAME as DESCRIPTION'])->where('INACTIVE', '0')->get();
        $this->rateType = RateType::all();
        $this->units = UnitOfMeasures::where('INACTIVE', '0')->get();
    }
    public function mount($id = null)
    {

        $this->LoadDropdown();

        if (is_numeric($id)) {

            $item = Items::where('ID', $id)->first();

            if ($item) {
                $this->ID = $item->ID;
                $this->CODE = $item->CODE;
                $this->DESCRIPTION = $item->DESCRIPTION;
                $this->PURCHASE_DESCRIPTION = $item->PURCHASE_DESCRIPTION;
                $this->GROUP_ID =  $item->GROUP_ID ? $item->GROUP_ID : 0;
                $this->SUB_CLASS_ID = $item->SUB_CLASS_ID ? $item->SUB_CLASS_ID : 0;
                $this->TYPE = $item->TYPE;
                $this->STOCK_TYPE = $item->STOCK_TYPE ? $item->STOCK_TYPE : 0;
                $this->GL_ACCOUNT_ID = $item->GL_ACCOUNT_ID ? $item->GL_ACCOUNT_ID : 0;
                $this->COGS_ACCOUNT_ID = $item->COGS_ACCOUNT_ID ? $item->COGS_ACCOUNT_ID : 0;
                $this->ASSET_ACCOUNT_ID = $item->ASSET_ACCOUNT_ID ?  $item->ASSET_ACCOUNT_ID : 0;
                $this->TAXABLE = $item->TAXABLE;
                $this->PREFERRED_VENDOR_ID = $item->PREFERRED_VENDOR_ID ? $item->PREFERRED_VENDOR_ID : 0;
                $this->MANUFACTURER_ID = $item->MANUFACTURER_ID ? $item->MANUFACTURER_ID : 0;
                $this->RATE = $item->RATE ? $item->RATE : 0;
                $this->COST = $item->COST ?  $item->COST : 0;
                $this->RATE_TYPE = $item->RATE_TYPE ? $item->RATE_TYPE : 0;
                $this->PAYMENT_METHOD_ID = $item->PAYMENT_METHOD_ID ? $item->PAYMENT_METHOD_ID : 0;
                $this->NOTES = $item->NOTES;
                $this->BASE_UNIT_ID = $item->BASE_UNIT_ID ? $item->BASE_UNIT_ID : 0;
                $this->PURCHASES_UNIT_ID = $item->PURCHASES_UNIT_ID ? $item->PURCHASES_UNIT_ID : 0;
                $this->SHIPPING_UNIT_ID = $item->SHIPPING_UNIT_ID ?  $item->SHIPPING_UNIT_ID : 0;
                $this->SALES_UNIT_ID = $item->SALES_UNIT_ID ? $item->SALES_UNIT_ID : 0;
                $this->PRINT_INDIVIDUAL_ITEMS = $item->PRINT_INDIVIDUAL_ITEMS ? $item->PRINT_INDIVIDUAL_ITEMS :  false;
                $this->INACTIVE = $item->INACTIVE;
                $this->CUSTOM_FIELD1 = $item->CUSTOM_FIELD1 ? $item->CUSTOM_FIELD1 : '';
                $this->CUSTOM_FIELD2 = $item->CUSTOM_FIELD2 ? $item->CUSTOM_FIELD2 : '';
                $this->CUSTOM_FIELD3 = $item->CUSTOM_FIELD3 ? $item->CUSTOM_FIELD3 : '';
                $this->CUSTOM_FIELD4 = $item->CUSTOM_FIELD4 ? $item->CUSTOM_FIELD4 : '';
                $this->CUSTOM_FIELD5 = $item->CUSTOM_FIELD5 ? $item->CUSTOM_FIELD5 : '';
                $this->NON_PORFOLIO_COMPUTATION = $item->NON_PORFOLIO_COMPUTATION ? $item->NON_PORFOLIO_COMPUTATION :  false;
                $this->BUNDLE_SET = $item->BUNDLE_SET  ? $item->BUNDLE_SET : false;
                $this->NON_DISCOUNTED_ITEM = $item->NON_DISCOUNTED_ITEM ? $item->NON_DISCOUNTED_ITEM : false;
                $this->PIC_FILENAME = $item->PIC_FILENAME ? $item->PIC_FILENAME : '';
                $this->IS_EXPIRED = $item->IS_EXPIRED ? $item->IS_EXPIRED : false;

                $getSubClass = ItemSubClass::where('ID', $this->SUB_CLASS_ID)->first();

                if ($getSubClass) {
                    $this->CLASS_ID = $getSubClass->CLASS_ID;
                    $this->updatedCLASSID();
                }

                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventoryitem')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = "";
        $this->DESCRIPTION = "";
        $this->PURCHASE_DESCRIPTION = "";
        $this->GROUP_ID = 0;
        $this->SUB_CLASS_ID = 0;
        $this->TYPE = 0;
        $this->STOCK_TYPE = 0;
        $this->GL_ACCOUNT_ID = 0;
        $this->COGS_ACCOUNT_ID = 0;
        $this->ASSET_ACCOUNT_ID = 0;
        $this->TAXABLE = false;
        $this->PREFERRED_VENDOR_ID = 0;
        $this->MANUFACTURER_ID = 0;
        $this->RATE = 0;
        $this->COST = 0;
        $this->RATE_TYPE = 0;
        $this->PAYMENT_METHOD_ID = 0;
        $this->NOTES = "";
        $this->BASE_UNIT_ID = 0;
        $this->PURCHASES_UNIT_ID = 0;
        $this->SHIPPING_UNIT_ID = 0;
        $this->SALES_UNIT_ID = 0;
        $this->PRINT_INDIVIDUAL_ITEMS = true;
        $this->INACTIVE = false;
        $this->CUSTOM_FIELD1 = "";
        $this->CUSTOM_FIELD2 = "";
        $this->CUSTOM_FIELD3 = "";
        $this->CUSTOM_FIELD4 = "";
        $this->CUSTOM_FIELD5 = "";
        $this->NON_PORFOLIO_COMPUTATION = false;
        $this->BUNDLE_SET = false;
        $this->NON_DISCOUNTED_ITEM = false;
        $this->PIC_FILENAME = "";
        $this->IS_EXPIRED = false;
    }
    public function updatedTYPE()
    {
        $this->itemGroup = ItemGroup::where('ITEM_TYPE', $this->TYPE)->get();
    }
    public function updatedCLASSID()
    {

        try {
            if ($this->CLASS_ID) {
                $this->itemSubClass = ItemSubClass::where('CLASS_ID', $this->CLASS_ID)->get();
            } else {
                $this->itemSubClass = [];
            }
        } catch (\Exception $e) {
            $this->itemSubClass = [];
        }
    }
    public function save(ItemServices $itemServices)
    {

        $this->validate([
            'CODE' => 'required|max:10|unique:item,code,' . $this->ID,
            'DESCRIPTION' => 'required|max:100|unique:item,description,' . $this->ID,
            'TYPE' => 'required'
        ]);

        try {
            $Message = '';
            if ($this->ID === 0) {
                $this->ID =  $itemServices->Store(
                    $this->CODE,
                    $this->DESCRIPTION,
                    $this->PURCHASE_DESCRIPTION,
                    $this->GROUP_ID,
                    $this->SUB_CLASS_ID,
                    $this->TYPE,
                    $this->STOCK_TYPE,
                    $this->GL_ACCOUNT_ID,
                    $this->COGS_ACCOUNT_ID,
                    $this->ASSET_ACCOUNT_ID,
                    $this->TAXABLE,
                    $this->PREFERRED_VENDOR_ID,
                    $this->MANUFACTURER_ID,
                    $this->RATE,
                    $this->COST,
                    $this->RATE_TYPE,
                    $this->PAYMENT_METHOD_ID,
                    $this->NOTES,
                    $this->BASE_UNIT_ID,
                    $this->PURCHASES_UNIT_ID,
                    $this->SHIPPING_UNIT_ID,
                    $this->SALES_UNIT_ID,
                    $this->PRINT_INDIVIDUAL_ITEMS,
                    $this->INACTIVE,
                    $this->CUSTOM_FIELD1,
                    $this->CUSTOM_FIELD2,
                    $this->CUSTOM_FIELD3,
                    $this->CUSTOM_FIELD4,
                    $this->CUSTOM_FIELD5,
                    $this->NON_PORFOLIO_COMPUTATION,
                    $this->BUNDLE_SET,
                    $this->NON_DISCOUNTED_ITEM,
                    $this->PIC_FILENAME,
                    $this->IS_EXPIRED
                );

                $Message = 'Successfully created.';
            } else {
            
                $itemServices->Update(
                    $this->ID,
                    $this->CODE,
                    $this->DESCRIPTION,
                    $this->PURCHASE_DESCRIPTION,
                    $this->GROUP_ID,
                    $this->SUB_CLASS_ID,
                    $this->TYPE,
                    $this->STOCK_TYPE,
                    $this->GL_ACCOUNT_ID,
                    $this->COGS_ACCOUNT_ID,
                    $this->ASSET_ACCOUNT_ID,
                    $this->TAXABLE,
                    $this->PREFERRED_VENDOR_ID,
                    $this->MANUFACTURER_ID,
                    $this->RATE,
                    $this->COST,
                    $this->RATE_TYPE,
                    $this->PAYMENT_METHOD_ID,
                    $this->NOTES,
                    $this->BASE_UNIT_ID,
                    $this->PURCHASES_UNIT_ID,
                    $this->SHIPPING_UNIT_ID,
                    $this->SALES_UNIT_ID,
                    $this->PRINT_INDIVIDUAL_ITEMS,
                    $this->INACTIVE,
                    $this->CUSTOM_FIELD1,
                    $this->CUSTOM_FIELD2,
                    $this->CUSTOM_FIELD3,
                    $this->CUSTOM_FIELD4,
                    $this->CUSTOM_FIELD5,
                    $this->NON_PORFOLIO_COMPUTATION,
                    $this->BUNDLE_SET,
                    $this->NON_DISCOUNTED_ITEM,
                    $this->PIC_FILENAME,
                    $this->IS_EXPIRED
                );
                $Message = 'Successfully updated.';
            }
            session()->flash('message',  $Message);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {

        return view('livewire.items-form');
    }
}
