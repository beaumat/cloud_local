<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\OtherServices;
use App\Services\ServiceChargeServices;
use Livewire\Component;

class PhilhealthAvailment extends Component
{
    public string $REPORT_HEADER_1;
    public string $LOGO_FILE;
    public int $id; // Patient ID
    public string $PHIC_NO;
    public string $CONTACT_NAME;
    public string $DONE_DATE;
    public string $FINAL_DIAGNOSIS;
    public int $TOTAL_DAYS;
    public int $YEAR;
    public string $BRANCH_NAME;
    public string $USER_NAME;
    public $dataList = [];
    private $contactServices;
    private $serviceChargeServices;
    private $locationServices;
    private $otherServices;
    public function boot(
        ContactServices $contactServices,
        ServiceChargeServices $serviceChargeServices,
        LocationServices $locationServices,
        OtherServices $otherServices
    ) {

        $this->contactServices = $contactServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->locationServices = $locationServices;
        $this->otherServices = $otherServices;
    }
    public function mount($id = null, int $year, int $locationid)
    {
        $contact = $this->contactServices->get($id, 3);
        if ($contact) {
            $this->id = $contact->ID;
            $extend = $contact->SALUTATION != '' ?  $contact->SALUTATION . ', ' : ', ';
            $this->CONTACT_NAME = $contact->LAST_NAME . ' ' .  $extend .  $contact->FIRST_NAME . ' ' .  $contact->MIDDLE_NAME;
            $this->PHIC_NO = $this->otherServices->PhilHlealthDigitFormat($contact->PIN ?? '');
            $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
            $this->YEAR = $year;
            $this->TOTAL_DAYS = (int) $this->serviceChargeServices->getAvailmentTotal($contact->ID, $year, $locationid);
            $lastData = $this->serviceChargeServices->getLastAvailment($id, $year, $locationid);
            if ($lastData) {
                $this->DONE_DATE = $this->otherServices->formatSpecialDate($lastData->DATE);
            } else {

                $this->DONE_DATE = '';
            }


            $locData =  $this->locationServices->get($locationid);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                $this->BRANCH_NAME = $locData->NAME_OF_BUSINESS ?? '';
                $user = $this->contactServices->get($locData->PHIC_INCHARGE_ID, 2);
                if ($user) {
                    $this->USER_NAME = $user->PRINT_NAME_AS ?? ' ';
                }
            }

            $this->dataList = $this->serviceChargeServices->getAvailList($contact->ID, $year, $locationid);
        }
    }

    public function render()
    {
        return view('livewire.patient.philhealth-availment');
    }
}
