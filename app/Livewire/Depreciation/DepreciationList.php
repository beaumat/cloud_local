<?php

namespace App\Livewire\Depreciation;

use App\Services\DepreciationServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Depreciation List')]
class DepreciationList extends Component
{

    use WithPagination;
    public int $perPage = 30;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];
    public $search = '';
    public int $locationid;
    public $locationList = [];
    private $depositServices;
    private $userServices;
    private $depreciationServices;
    private $locationServices;
    public function boot(DepreciationServices $depreciationServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->depreciationServices = $depreciationServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        
        $this->locationid = $this->userServices->getLocationDefault();
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
    public function render()
    {

        $data = $this->depreciationServices->Search($this->search, $this->locationid, $this->perPage);

        return view('livewire.depreciation.depreciation-list', ['dataList' => $data]);
    }
}
