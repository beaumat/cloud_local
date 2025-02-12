<?php

namespace App\Livewire\TaxCredit;

use App\Services\LocationServices;
use App\Services\TaxCreditServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tax Credit')]
class TaxCreditList extends Component
{
    use WithPagination;

    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    public int $locationid;

    public $search;
    public $locationList = [];

    private $taxCreditServices;
    private $locationServices;
    private $userServices;
    public function boot(TaxCreditServices $taxCreditServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->taxCreditServices = $taxCreditServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid =  $this->userServices->getLocationDefault();
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
            $this->userServices->SwapLocation($this->locationid);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function delete(int $id) {
        
    }
    public function render()
    {

        $data = $this->taxCreditServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.tax-credit.tax-credit-list', ['dataList' => $data]);
    }
}
