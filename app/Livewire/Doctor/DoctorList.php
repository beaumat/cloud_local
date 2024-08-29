<?php

namespace App\Livewire\Doctor;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Doctors')]
class DoctorList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public int $perPage = 15;
    public int $locationid = 0;
    private $contactServices;
    public $locationList = [];
    private $locationServices;
    private $userServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
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
    public function mount()
    {
     
        $this->locationid = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();

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
        $dataList =  $this->contactServices->Search($this->search, 4, 15, $this->locationid);
        return view('livewire.doctor.doctor-list', ['dataList' => $dataList]);
    }
}
