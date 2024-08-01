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
    public function boot( PhilHealthServices $philHealthServices, LocationServices $locationServices, UserServices $userServices ) {
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
    #[On('reload-list')]
    public function render()
    {
        $dataList = $this->philHealthServices->Search($this->search, $this->locationid, $this->perPage);
        return view('livewire.phil-health.phil-health-list', ['dataList' => $dataList]);
    }
}
