<?php

namespace App\Livewire\Invoice;

use App\Services\InvoiceServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Invoice')]
class InvoiceList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public int $locationid;
    public $locationList = [];
    private $invoiceServices;
    private $locationServices;
    private $userServices;

    public function boot(InvoiceServices $invoiceServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->invoiceServices = $invoiceServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            $this->invoiceServices->Delete($id);
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
        $dataList = $this->invoiceServices->Search($this->search, $this->locationid, $this->perPage);
        
        return view('livewire.invoice.invoice-list', ['dataList' => $dataList]);
    }
}
