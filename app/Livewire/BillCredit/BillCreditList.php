<?php

namespace App\Livewire\BillCredit;

use App\Services\BillCreditServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;


#[Title('Bill Credits')]
class BillCreditList extends Component
{
    use WithPagination;
    public int $perPage = 15;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $billCreditServices;
    private $locationServices;
    private $userServices;

    public function boot(
        BillCreditServices $billCreditServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->billCreditServices = $billCreditServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete(int $ID)
    {
        try {
            DB::beginTransaction();
            $this->billCreditServices->Delete($ID);
            DB::commit();
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $dataList = $this->billCreditServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.bill-credit.bill-credit-list', ['dataList' => $dataList]);
    }
}
