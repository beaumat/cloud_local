<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPUnit\Framework\isEmpty;

class QuickCreate extends Component
{
    public $patientSelected = [];
    public bool $SelectAll = false;
    public $locationList = [];
    public int $LOCATION_ID;
    public bool $showModal = false;
    private $locationServices;
    private $userServices;
    public $DATE_FROM;
    public $DATE_TO;
    public $search;
    public $dataList = [];
    private $hemoServices;
    private $philHealthServices;
    private $contactServices;
    private $dateServices;
    public function boot(
        LocationServices $locationServices,
        UserServices $userServices,
        HemoServices $hemoServices,
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        DateServices $dateServices
    ) {
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->hemoServices = $hemoServices;
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->dateServices = $dateServices;
    }
    public function ResetValue()
    {
        $this->SelectAll = false;
        $this->patientSelected = [];
        $this->LoadList();
    }
    public function updatedDateFrom()
    {
        $this->ResetValue();
    }
    public function updatedDateTo()
    {
        $this->ResetValue();
    }
    public function updatedLocationId()
    {
        $this->ResetValue();
    }

    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->DATE_FROM = $this->dateServices->NowDate();
        $this->DATE_TO = $this->dateServices->NowDate();
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            foreach ($this->dataList as $list) {
                $this->patientSelected[$list->ID] = true;
            }
        } else {
            $this->patientSelected = [];
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    private $DATE_ADMITTED = '';
    private $TIME_ADMITTED = '';
    private $DATE_DISCHARGED = '';
    private $TIME_DISCHARGED = '';
    private string $FINAL_DIAGNOSIS = '';
    private string $OTHER_DIAGNOSIS = '';
    private string $FIRST_CASE_RATE = '';
    private string $SECOND_CASE_RATE = '';

    private function generateDateTime($CONTACT_ID): bool
    {

        $data = $this->hemoServices->getDateTimeByRange($CONTACT_ID, $this->LOCATION_ID, $this->DATE_FROM, $this->DATE_TO);
        if ($data) {

            dd($data);
            $this->DATE_ADMITTED = $data['FIRST_DATE'];
            $this->TIME_ADMITTED = $data['FIRST_TIME'];
            $this->DATE_DISCHARGED = $data['LAST_DATE'];
            $this->TIME_DISCHARGED = $data['LAST_TIME'];
            return true;
        }

        return false;
    }
    private function resetMethod()
    {
        $this->dispatch('reload-list',);
        $this->ResetValue();
        $this->showModal = false;
    }
    public function generateRemarks($CONTACT_ID)
    {
        $contact = $this->contactServices->get($CONTACT_ID, 3);

        if ($contact) {
            $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
            $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
            $this->FIRST_CASE_RATE = $contact->FIRST_CASE_RATE ?? '';
            $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
        }
    }
    public function create()
    {
        DB::beginTransaction();
        try {


            $gotSelected = false;
            foreach ($this->patientSelected as $patientID => $isSelected) {
                if ($isSelected) {
                    $gotSelected = true;
                    if ($this->generateDateTime($patientID)) {

                        if (empty($this->DATE_ADMITTED) == false && empty($this->DATE_DISCHARGED) == false) {
                            $this->generateRemarks($patientID);
                            $ID = (int) $this->philHealthServices->preSave(
                                '',
                                $this->dateServices->NowDate(),
                                $this->LOCATION_ID,
                                $patientID,
                                $this->DATE_ADMITTED,
                                $this->TIME_ADMITTED,
                                $this->DATE_DISCHARGED,
                                $this->TIME_DISCHARGED,
                                $this->FINAL_DIAGNOSIS,
                                $this->OTHER_DIAGNOSIS,
                                $this->FIRST_CASE_RATE,
                                $this->SECOND_CASE_RATE
                            );
                            $this->philHealthServices->DefaultEntry($ID);
                        }
                    }
                }
            }

            if ($gotSelected == true) {
                $this->resetMethod();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    private function LoadList() {}

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        $this->dataList = $this->hemoServices->QuickFilterByDateRange($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, $this->search);

        return view('livewire.phil-health.quick-create');
    }
}
