<?php

namespace App\Livewire\BankRecon;

use App\Services\BankReconServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CheckPayment extends Component
{
    #[Reactive]
    public int $ACCOUNT_RECONCILIATION_ID;
    #[Reactive]
    public int $ACCOUNT_ID;
    public int $LOCATION_ID;
    public $locationList = [];
    public bool $showModal = false;
    public $search;
    public $dataList = [];
    private $bankReconServices;
    private $locationServices;
    private $userServices;
    public function boot(BankReconServices $bankReconServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->bankReconServices = $bankReconServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    #[On('open-check')]
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function AddItem(int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE, float $AMOUNT)
    {
        try {
            $this->bankReconServices->ItemStore(
                $this->ACCOUNT_RECONCILIATION_ID,
                $OBJECT_ID,
                $OBJECT_TYPE,
                $OBJECT_DATE,
                $ENTRY_TYPE,
                $AMOUNT
            );
            $this->dispatch('refresh-item');
            $this->dispatch('refresh-details');
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
        }
    }
    #[On('refresh-item')]
    public function render()
    {
        $this->dataList = $this->bankReconServices->getPaymentList(
            $this->ACCOUNT_ID,
            $this->LOCATION_ID,
            1,
            $this->search
        );
        return view('livewire.bank-recon.check-payment');
    }
}
