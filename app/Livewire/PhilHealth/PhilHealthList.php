<?php

namespace App\Livewire\PhilHealth;

use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Philhealth')]
class PhilHealthList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 20;
    public int $locationid;
    public $locationList = [];
    private $philHealthServices;
    private $locationServices;
    private $userServices;
    public bool $show = false;
    public function boot(PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->philHealthServices = $philHealthServices;
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
        $this->philHealthServices->Delete($id);
    }
    public function multiselect()
    {
        $this->show = true;
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
    public function getARForm(int $ID)
    {
        $data = [
            'PHILHEALTH_ID' => $ID
        ];

        $this->dispatch('ar-form-show', result: $data);
    }
    #[On('ar-form-data')]
    public function arForm($ar)
    {
        $this->dispatch('reload_philhealth_payment');
    }
    public function print(int $ID)
    {
        $data = [
            'PHILHEALTH_ID' => $ID
        ];
        
        $this->dispatch('philhealth-print-data', result: $data);
    }

    #[On('reload-list')]
    public function render()
    {
        $dataList = $this->philHealthServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.phil-health.phil-health-list', ['dataList' => $dataList]);
    }
}
