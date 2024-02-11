<?php

namespace App\Livewire\PurchaseOrder;

use App\Models\Contacts;
use App\Models\DocumentStatus;
use App\Models\Locations;
use App\Models\PaymentTerms;
use App\Models\PurchaseOrder;
use App\Models\ShipVia;
use App\Models\Tax;
use App\Services\PurchaseOrderServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Purchase Order')]
class PurchaseOrderForm extends Component
{
    public int $ID;
    public int $VENDOR_ID;
    public string $DATE;
    public string $CODE;
    public int $LOCATION_ID;
    public int $CLASS_ID;
    public int $SHIP_VIA_ID;
    public string $DATE_EXPECTED;
    public int $PAYMENT_TERMS_ID;
    public string $NOTES;
    public int $STATUS;
    public string $STATUS_DESCRIPTION;
    public int $INPUT_TAX_ID;
    public float $INPUT_TAX_RATE;
    public int $INPUT_TAX_VAT_METHOD;
    public int $INPUT_TAX_ACCOUNT_ID;

    public float $INPUT_TAX_AMOUNT;
    public float $AMOUNT;
    public float $TAXABLE_AMOUNT;
    public float $NONTAXABLE_AMOUNT;
    public $vendorList = [];
    public $locationList = [];
    public $shipViaList = [];
    public $paymentTermList = [];
    public $taxList = [];
    public bool $Modify;
    public function LoadDropdown()
    {
        $this->vendorList = Contacts::query()->select(['ID', 'NAME'])->where('TYPE', '0')->where('INACTIVE', '0')->get();
        $this->locationList = Locations::query()->select(['ID', 'NAME'])->where('INACTIVE', '0')->get();
        $this->shipViaList = ShipVia::all();
        $this->paymentTermList = PaymentTerms::query()->select(['ID', 'DESCRIPTION'])->where('INACTIVE', '0')->get();
        $this->taxList = Tax::query()->select(['ID', 'NAME'])->where('TAX_TYPE', 3)->get();
    }
    public function getTax()
    {
        $tax = Tax::where('ID', $this->INPUT_TAX_ID)->first();
        if ($tax) {
            $this->INPUT_TAX_RATE = (float) $tax->INPUT_TAX_RATE;
            $this->INPUT_TAX_VAT_METHOD = (int) $tax->VAT_METHOD;
            $this->INPUT_TAX_ACCOUNT_ID = (int) $tax->TAX_ACCOUNT_ID;
        }
    }

    private function getInfo($PO)
    {
        $this->ID = $PO->ID;
        $this->CODE = $PO->CODE;
        $this->DATE = $PO->DATE;
        $this->DATE_EXPECTED = $PO->DATE_EXPECTED ? $PO->DATE_EXPECTED : '';
        $this->LOCATION_ID = $PO->LOCATION_ID;
        $this->VENDOR_ID = $PO->VENDOR_ID;
        $this->SHIP_VIA_ID = $PO->SHIP_VIA_ID ? $PO->SHIP_VIA_ID : 0;
        $this->PAYMENT_TERMS_ID = $PO->PAYMENT_TERMS_ID ? $PO->PAYMENT_TERMS_ID : 0;
        $this->CLASS_ID = $PO->CLASS_ID ? $PO->CLASS_ID : 0;
        $this->NOTES = $PO->NOTES;
        $this->AMOUNT = $PO->AMOUNT;
        $this->STATUS = $PO->STATUS;
        $this->INPUT_TAX_ID = $PO->INPUT_TAX_ID ? $PO->INPUT_TAX_ID : 0;
        $this->INPUT_TAX_RATE = $PO->INPUT_TAX_RATE ? $PO->INPUT_TAX_RATE : 0;
        $this->INPUT_TAX_AMOUNT = $PO->INPUT_TAX_AMOUNT ? $PO->INPUT_TAX_AMOUNT : 0;
        $this->INPUT_TAX_VAT_METHOD = $PO->INPUT_TAX_VAT_METHOD ? $PO->INPUT_TAX_VAT_METHOD : 0;
        $this->INPUT_TAX_ACCOUNT_ID = $PO->INPUT_TAX_ACCOUNT_ID ? $PO->INPUT_TAX_ACCOUNT_ID : 0;
        $this->TAXABLE_AMOUNT = $PO->TAXABLE_AMOUNT ? $PO->TAXABLE_AMOUNT : 0;
        $this->NONTAXABLE_AMOUNT = $PO->NONTAXABLE_AMOUNT ? $PO->NONTAXABLE_AMOUNT : 0;
        $this->STATUS_DESCRIPTION = DocumentStatus::where('ID', $this->STATUS)->first()->DESCRIPTION;
    }
    public function mount($id = null)
    {
        $this->LoadDropdown();
        if (is_numeric($id)) {
            $PO = PurchaseOrder::where('ID', $id)->first();
            if ($PO) {
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('vendorspurchase_order')->with('error', $errorMessage);
        }

        $this->Modify = true;
        $this->ID = 0;
        $this->CODE = '';
        $currentDate = Carbon::now();
        $this->DATE = $currentDate->format('Y-m-d');
        $this->DATE_EXPECTED = '';
        $this->LOCATION_ID = Auth::user()->location_id ?? Locations::first()->ID;
        $this->VENDOR_ID = 0;
        $this->SHIP_VIA_ID = ShipVia::first()->ID;
        $this->CLASS_ID = 0;
        $this->PAYMENT_TERMS_ID = PaymentTerms::first()->ID;
        $this->NOTES = '';
        $this->AMOUNT = 0;
        $this->STATUS = DocumentStatus::where('DESCRIPTION', 'Draft')->first()->ID;
        $this->INPUT_TAX_ID = Tax::where('TAX_TYPE', 3)->first()->ID;
        $this->INPUT_TAX_RATE = 0;
        $this->INPUT_TAX_AMOUNT = 0;
        $this->INPUT_TAX_VAT_METHOD = 0;
        $this->INPUT_TAX_ACCOUNT_ID = 0;
        $this->TAXABLE_AMOUNT = 0;
        $this->NONTAXABLE_AMOUNT = 0;
        $this->STATUS_DESCRIPTION = "";
        $this->getTax();
    }
    public function getModify()
    {
        $this->Modify = true;
    }
    public function save(PurchaseOrderServices $purchaseOrderServices)
    {




        try {
            if ($this->ID == 0) {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );



                $this->getTax();
                $this->ID = $purchaseOrderServices->Store($this->CODE, $this->DATE, $this->VENDOR_ID, $this->LOCATION_ID, $this->CLASS_ID, $this->DATE_EXPECTED, '', $this->SHIP_VIA_ID, $this->PAYMENT_TERMS_ID, $this->NOTES, $this->STATUS, $this->INPUT_TAX_ID, $this->INPUT_TAX_RATE, $this->INPUT_TAX_VAT_METHOD, $this->INPUT_TAX_ACCOUNT_ID);

                return Redirect::route('vendorspurchase_order_edit', ['id' => $this->ID])->with('message', 'Successfully created');

            } else {

                $this->validate(
                    [
                        'VENDOR_ID' => 'required|not_in:0',
                        'CODE' => 'required|max:20|unique:purchase_order,code,' . $this->ID,
                        'INPUT_TAX_ID' => 'required|not_in:0',
                        'DATE' => 'required',
                        'LOCATION_ID' => 'required',
                        'PAYMENT_TERMS_ID' => 'required'
                    ],
                    [],
                    [
                        'VENDOR_ID' => 'Vendor',
                        'CODE' => 'Reference No.',
                        'INPUT_TAX_ID' => 'Tax',
                        'DATE' => 'Date',
                        'LOCATION_ID' => 'Location',
                        'PAYMENT_TERMS_ID' => 'Payment Terms'
                    ]
                );


                $this->getTax();
                $purchaseOrderServices->Update($this->ID, $this->CODE, $this->DATE, $this->VENDOR_ID, $this->LOCATION_ID, $this->CLASS_ID, $this->DATE_EXPECTED, '', $this->SHIP_VIA_ID, $this->PAYMENT_TERMS_ID, $this->NOTES, $this->STATUS, $this->INPUT_TAX_ID, $this->INPUT_TAX_RATE, $this->INPUT_TAX_VAT_METHOD, $this->INPUT_TAX_ACCOUNT_ID);
                $purchaseOrderServices->getUpdateTaxItem($this->ID, $this->INPUT_TAX_ID);
                $getResult = $purchaseOrderServices->ReComputed($this->ID);
                $this->getUpdateAmount($getResult);
                session()->flash('message', 'Successfully updated');


            }
            $PO = PurchaseOrder::where('ID', $this->ID)->first();
            if ($PO) {
                $this->getInfo($PO);
            }
            $this->Modify = false;
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('update-amount')]
    public function getUpdateAmount($result)
    {
        foreach ($result as $list) {
            $this->AMOUNT = $list['AMOUNT'];
            $this->INPUT_TAX_AMOUNT = $list['TAX_AMOUNT'];
            $this->TAXABLE_AMOUNT = $list['TAXABLE_AMOUNT'];
            $this->NONTAXABLE_AMOUNT = $list['NONTAXABLE_AMOUNT'];
        }
    }
    public function updateCancel()
    {
        $PO = PurchaseOrder::where('ID', $this->ID)->first();
        if ($PO) {
            $this->getInfo($PO);
        }
        $this->Modify = false;
    }
    public function getSubmit(PurchaseOrderServices $purchaseOrderServices)
    {
        try {
            $purchaseOrderServices->StatusUpdate($this->ID, 2);
            $PO = PurchaseOrder::where('ID', $this->ID)->first();
            if ($PO) {
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }

    }
    public function getVoid(PurchaseOrderServices $purchaseOrderServices)
    {
        try {

            $purchaseOrderServices->StatusUpdate($this->ID, 7);
            $PO = PurchaseOrder::where('ID', $this->ID)->first();
            if ($PO) {
                $this->getInfo($PO);
                $this->Modify = false;
                return;
            }

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.purchase-order.purchase-order-form');
    }
}
