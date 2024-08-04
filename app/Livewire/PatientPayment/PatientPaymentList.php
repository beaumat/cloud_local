<?php

namespace App\Livewire\PatientPayment;

use App\Services\LocationServices;
use App\Services\PatientPaymentServices;
use App\Services\UploadServices;
use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Patient Payment List')]
class PatientPaymentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public bool $isDesc = true;
    public int $perPage = 20;
    public string $sortby = 'patient_payment.ID';
    public int $locationid;
    public $locationList = [];
    private $patientPaymentServices;
    private $locationServices;
    private $userServices;
    private $uploadServices;
    public function boot(PatientPaymentServices $patientPaymentServices, LocationServices $locationServices, UserServices $userServices, UploadServices $uploadServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->uploadServices = $uploadServices;
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->locationid = $this->userServices->getLocationDefault();
    }
    public function delete($id)
    {
        try {
            $data = $this->patientPaymentServices->get($id);
            if ($data) {
                $this->uploadServices->RemoveIfExists($data->FILE_PATH);
                $this->patientPaymentServices->Delete($data->ID);
                session()->flash('message', 'Successfully deleted.');
            }
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
    public function getConfirm($id)
    {
        $this->patientPaymentServices->ConfirmProccess($id);
    }
    public function reloadList()
    {
        $this->dispatch('reload-list');
    }
    public function openPayment(int $CONTACT_ID)
    {
        $data = [
            'CONTACT_ID' => $CONTACT_ID
        ];
        $this->dispatch('open-assistance', result: $data);
    }
    public function sorting(string $column)
    {
        if ($this->sortby  == $column) {
            $this->isDesc = $this->isDesc ? false : true;
            return;
        }
        $this->isDesc = true;
        $this->sortby = $column;
    }
    #[On('reload-list')]
    public function render()
    {
        $dataList = $this->patientPaymentServices->Search($this->search, $this->locationid, $this->perPage, $this->sortby, $this->isDesc);
        return view('livewire.patient-payment.patient-payment-list', ['dataList' => $dataList]);
    }
}
