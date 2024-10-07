<?php

namespace App\Livewire\TaxCredit;

use App\Services\TaxCreditServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class InvoiceList extends Component
{
    #[Reactive]
    public int $TAX_CREDIT_ID;
    #[Reactive]
    public int $STATUS;
    #[Reactive]
    public int $CUSTOMER_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public float $AMOUNT;

    public $dataList = [];
    private $taxCreditServices;
    public function boot(TaxCreditServices $taxCreditServices)
    {
        $this->taxCreditServices = $taxCreditServices;
    }
    

    public function render()
    {
        $this->dataList = $this->taxCreditServices->GetInvoiceist($this->TAX_CREDIT_ID);
        return view('livewire.tax-credit.invoice-list');
    }
}
