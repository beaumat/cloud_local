<?php

namespace App\Livewire\CustomerReport;

use App\Services\CustomerServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PaymentMethodServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title("Customer Sales Report")]
class CustomerSalesReport extends Component
{
    public float $TOTAL  = 0;
    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public int $PAYMENT_METHOD_ID;
    public $paymentMethodList = [];

    public $dataList = [];

    private $customerServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $paymentMethodServices;
    public function boot(
        CustomerServices $customerServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        PaymentMethodServices $paymentMethodServices
    ) {
        $this->customerServices = $customerServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->paymentMethodServices = $paymentMethodServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();

        $this->locationList = $this->locationServices->getList();
        $this->paymentMethodList = $this->paymentMethodServices->getListNonPatient();
        $this->PAYMENT_METHOD_ID = 0;
    }

    public function generate()
    {
        try {
            $this->TOTAL = 0;
            $this->dataList  = $this->customerServices->GenerateSales($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, $this->PAYMENT_METHOD_ID);
        } catch (\Exception $ex) {
            session()->flash('error', $ex->getMessage());
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function updatedlocationid()
    {
        try {
            $this->dataList = [];
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.customer-report.customer-sales-report');
    }
}
