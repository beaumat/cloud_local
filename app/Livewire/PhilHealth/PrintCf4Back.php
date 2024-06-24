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
    public $dataMed = [];
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
        $getData = ['GENERIC_NAME' => '', 'QUANTITY' => '', 'DOSSAGE' => '', 'ROUTE' => '', 'FREQUENCY' => '', 'TOTAL_COST' => '', 'CONT_GENERIC_NAME' => '', 'CONT_QUANTITY' => '', 'CONT_DOSSAGE' => '', 'CONT_ROUTE' => '', 'CONT_FREQUENCY' => '', 'CONT_TOTAL_COST' => ''];

        $this->dataMed = [
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
            [$getData],
        ];

        $this->getMed($id);

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
    public function getMed(int $ID)
    {
        for ($i = 0; $i < 8; $i++) {
            // first Initialize default value.
            $this->dataMed[$i]['GENERIC_NAME'] = '';
            $this->dataMed[$i]['QUANTITY'] = '';
            $this->dataMed[$i]['DOSSAGE'] = '';
            $this->dataMed[$i]['ROUTE'] = '';
            $this->dataMed[$i]['FREQUENCY'] = '';
            $this->dataMed[$i]['TOTAL_COST'] = '';

            $this->dataMed[$i]['CONT_GENERIC_NAME'] = '';
            $this->dataMed[$i]['CONT_QUANTITY'] = '';
            $this->dataMed[$i]['CONT_DOSSAGE'] = '';
            $this->dataMed[$i]['CONT_ROUTE'] = '';
            $this->dataMed[$i]['CONT_FREQUENCY'] = '';
            $this->dataMed[$i]['CONT_TOTAL_COST'] = '';
        }

        $dt = $this->philHealthServices->DrugMedicineList($ID);
        $r = 0;

        foreach ($dt as $list) {
            if ($r == 7) {
                return;
            }
            $this->dataMed[$r]['GENERIC_NAME'] = $list->GENERIC_NAME ?? '';
            $this->dataMed[$r]['QUANTITY'] = number_format($list->QUANTITY ?? 0, 0);
            $this->dataMed[$r]['DOSSAGE'] = $list->DOSSAGE ?? '';
            $this->dataMed[$r]['ROUTE'] = $list->ROUTE ?? '';
            $this->dataMed[$r]['FREQUENCY'] = $list->FREQUENCY ?? '';
            $this->dataMed[$r]['TOTAL_COST'] = number_format($list->TOTAL_COST, 2);

            $this->dataMed[$r]['CONT_GENERIC_NAME'] = $list->CONT_GENERIC_NAME;
            $this->dataMed[$r]['CONT_QUANTITY'] = number_format($list->CONT_QUANTITY, 0);
            $this->dataMed[$r]['CONT_DOSSAGE'] = $list->CONT_DOSSAGE;
            $this->dataMed[$r]['CONT_ROUTE'] = $list->CONT_ROUTE;
            $this->dataMed[$r]['CONT_FREQUENCY'] = $list->CONT_FREQUENCY;
            $this->dataMed[$r]['CONT_TOTAL_COST'] = number_format($list->CONT_TOTAL_COST, 2);
            $r++;
        }
    }
    public function render()
    {
        return view('livewire.phil-health.print-cf4-back');
    }
}
