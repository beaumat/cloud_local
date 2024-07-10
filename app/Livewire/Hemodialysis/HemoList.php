<?php

namespace App\Livewire\Hemodialysis;

use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Hemodialysis Treatment')]
class HemoList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];
    public $search = '';
    public int $perPage = 25;
    public int $locationid;
    public $locationList = [];
    private $hemoServices;
    private $locationServices;
    private $userServices;

    public string $DATE_FROM;
    public string $DATE_TO;
    private $dateServices;
    public function boot(HemoServices $hemoServices, LocationServices $locationServices, UserServices $userServices, DateServices $dateServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function delete($id)
    {
        try {
            $this->hemoServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount()
    {

        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function refreshList()
    {
        $this->dispatch('refresh-list');
    }
    #[On('refresh-list')]
    public function render()
    {
        $dataList = $this->hemoServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->DATE_FROM == '' ? $this->dateServices->NowDate() : $this->DATE_FROM,
            $this->DATE_TO == '' ? $this->dateServices->NowDate() : $this->DATE_TO
        );
        return view('livewire.hemodialysis.hemo-list', ['dataList' => $dataList]);
    }
}
