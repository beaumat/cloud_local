<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\PhilHealthServices;
use Livewire\Component;



class PrintCf4Back extends Component
{

    private $philHealthServices;
    private $hemoServices;
    private $contactServices;
    public string $DR_NAME;
    public $dateList = [];
    public $DOCTOR_ORDER = "UNDERGO HEMODIALYSIS TREATMENT WITH NO COMPLICATIONS";
    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
    }
    public function mount($id = null)
    {
        $data = $this->philHealthServices->get($id);
        if ($data) {

            $getData = $this->hemoServices->GetSummary(
                $data->CONTACT_ID,
                $data->LOCATION_ID,
                $data->DATE_ADMITTED,
                $data->DATE_DISCHARGED
            );
            $r = 0;
            foreach ($getData as $item) {
                $this->dateList[$r] = $item->DATE;
                $r++;
            }

            for ($i = $r; $i < 15; $i++) {
                $this->dateList[$i] = null;
            }



        }
        $fee = $this->philHealthServices->getProfFee($id);
        foreach ($fee as $list) {
            $this->DR_NAME = $list->NAME;
            return;
        }

    }
    public function render()
    {
        return view('livewire.phil-health.print-cf4-back');
    }
}
