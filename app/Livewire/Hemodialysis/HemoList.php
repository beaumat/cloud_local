<?php

namespace App\Livewire\Hemodialysis;

use App\Exports\TreatmentListExport;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Hemodialysis Treatment')]
class HemoList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search' => ['except' => '']];
    public $search = '';
    public $statusList = [];
    public int $perPage = 100;
    public int $locationid;
    public int $statusid = 0;
    public $locationList = [];
    private $hemoServices;
    private $locationServices;
    private $userServices;
    public $pendingList = [];

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

    #[On('upload-alert')]
    public function AlertMsg($data)
    {
        session()->flash('message', $data);
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
        $this->statusList = $this->hemoServices->HemoStatus();
    }
    public function refreshList()
    {
        $this->dispatch('refresh-list');
    }
    public function exportData()
    {
        return Excel::download(new TreatmentListExport(
            $this->hemoServices,
            $this->locationid,
            $this->search,
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->statusid
        ), 'hemo-treatment-.xlsx');
    }
    #[On('refresh-list')]
    public function render()
    {

        $this->pendingList = $this->hemoServices->UnpostedTratment($this->locationid, $this->search);

        $dataList = $this->hemoServices->Search(
            $this->search,
            $this->locationid,
            $this->perPage,
            $this->DATE_FROM == '' ? $this->dateServices->NowDate() : $this->DATE_FROM,
            $this->DATE_TO == '' ? $this->dateServices->NowDate() : $this->DATE_TO,
            $this->statusid
        );
        return view('livewire.hemodialysis.hemo-list', ['dataList' => $dataList]);
    }
}
