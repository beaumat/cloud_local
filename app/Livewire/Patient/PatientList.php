<?php

namespace App\Livewire\Patient;

use App\Exports\PatientListExport;
use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Patients')]
class PatientList extends Component
{
    public $contacts = [];

    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search = '';
    public int $perPage = 50;
    public $locationList = [];
    public $doctorList = [];
    public int $locationid;
    public int $doctorid;
    private $contactServices;
    private $locationServices;
    private $userServices;

    public function boot(ContactServices $contactServices, LocationServices $locationServices, UserServices $userServices)
    {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
        $this->doctorList = $this->contactServices->getList(4);
        $this->doctorid = 0;
    }
    public function delete($id)
    {
        try {
            $this->contactServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function export()
    {
        return Excel::download(new PatientListExport(
            $this->contactServices,
            $this->doctorid,
            $this->locationid,
            $this->search,
            $this->sortby,
            $this->isDesc
        ), 'patient-list.xlsx');
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public bool $isDesc = true;
    public string $sortby = 'contact.ID';
    public function sorting(string $column)
    {
        if ($this->sortby  == $column) {
            $this->isDesc = $this->isDesc ? false : true;
            return;
        }
        $this->isDesc = true;
        $this->sortby = $column;
    }
    public function render()
    {

        $dataList = $this->contactServices->SearchPatient($this->search, $this->perPage, $this->locationid, $this->sortby, $this->isDesc, $this->doctorid);

        return view('livewire.patient.patient-list', ['dataList' => $dataList]);
    }
}
