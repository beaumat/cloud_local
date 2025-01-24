<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Medical Certificate')]

class MedcertPrint extends Component
{
    public $id;
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public $BRANCH_NAME;
    public $LOGO_FILE;
    private $contactServices;
    private $dateServices;
    private $locationServices;
    public function boot(ContactServices $contactServices,DateServices $dateServices, LocationServices $locationServices) {
        $this->contactServices = $contactServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
    }
    public function mount($id = null)
    {   
        $this->id = $id;
        $data = $this->contactServices->get($id,3);

        if($data) {










            $locData =  $this->locationServices->get($data->LOCATION_ID);

            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                $this->BRANCH_NAME = $locData->NAME_OF_BUSINESS ?? '';
                $user = $this->contactServices->get($locData->PHIC_INCHARGE_ID, 2);
                // if ($user) {
                //     $this->USER_NAME = $user->PRINT_NAME_AS ?? ' ';
                // }
            }







            $this->dispatch('preview_print');
        }

    }
    
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {   
   
        return view('livewire.patient.medcert-print');
    }
}
