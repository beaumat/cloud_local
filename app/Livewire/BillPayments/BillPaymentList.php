<?php

namespace App\Livewire\BillPayments;

use App\Services\BillPaymentServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bill Payments')]
class BillPaymentList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    private $billPaymentServices;
    public function boot(
        BillPaymentServices $billPaymentServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->billPaymentServices = $billPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete(int $id)
    {

        try {
            $this->billPaymentServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');

        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        $dataList = $this->billPaymentServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.bill-payments.bill-payment-list', ['dataList' => $dataList]);
    }
}
