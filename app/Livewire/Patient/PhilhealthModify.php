<?php

namespace App\Livewire\Patient;

use App\Services\DateServices;
use App\Services\PhilHealthServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class PhilhealthModify extends Component
{
    #[Reactive]
    public int $PATIENT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public bool $showModal = false;
    public int $YEAR;
    public int $NO_OF_USED;
    public string $NOTES;

    public $E_ID = null;
    public $E_YEAR   = 0;
    public $E_NO_OF_USED;
    public $E_NOTES;
    public int $TOTAL;
    public $dataList = [];
    private $philHealthServices;

    private $dateServices;
    private $serviceChargeServices;
    public function boot(PhilHealthServices $philHealthServices, DateServices $dateServices, ServiceChargeServices $serviceChargeServices)
    {
        $this->dateServices = $dateServices;
        $this->philHealthServices = $philHealthServices;
        $this->serviceChargeServices = $serviceChargeServices;
    }
    public function Add()
    {

        $this->validate(
            [
                'YEAR'          => 'required|numeric|not_in:0',
                'NO_OF_USED'    => 'required|numeric|not_in:0'
            ],
            [],
            [
                'YEAR'          => 'Year',
                'NO_OF_USED'    => 'No of Used'
            ]
        );

        try {
            $this->philHealthServices->ItemAdjustStore(
                $this->PATIENT_ID,
                $this->LOCATION_ID,
                $this->NO_OF_USED,
                $this->YEAR,
                $this->NOTES
            );

            $this->NO_OF_USED = 0;
            $this->YEAR = $this->dateServices->NowYear();
            $this->NOTES = '';
            session()->flash('message', 'Successfully added');
        } catch (\Exception $e) {
            session()->flash('error', 'Error :' . $e->getMessage());
        }
    }

    private function PhicCount(): int
    {
        $count = $this->serviceChargeServices->GetCountByYear(
            $this->philHealthServices->PHIL_HEALTH_ITEM_ID,
            $this->dateServices->NowYear(),
            $this->PATIENT_ID,
            $this->LOCATION_ID
        );
        $countAdjust = $this->philHealthServices->ItemAdjustGet(
            $this->PATIENT_ID,
            $this->LOCATION_ID,
            $this->dateServices->NowYear()
        );
        return  $count + $countAdjust;
    }
    public function Delete(int $ID)
    {

        $this->philHealthServices->ItemAdjustDelete($ID);
    }
    public function Canceled()
    {
        $this->E_ID = null;
    }
    public function Edit(int $ID)
    {



        $data = $this->philHealthServices->GetItemAdjust($ID);
        if ($data) {
            $this->E_ID = $data->ID;
            $this->E_YEAR = $data->YEAR;
            $this->E_NO_OF_USED = $data->NO_OF_USED ?? 0;
            $this->E_NOTES = $data->NOTES ?? '';
        }
    }
    public function Update()
    {
        $this->validate(
            [
                'E_YEAR'          => 'required|numeric|not_in:0',
                'E_NO_OF_USED'    => 'required|numeric|not_in:0'
            ],
            [],
            [
                'E_YEAR'          => 'Year',
                'E_NO_OF_USED'    => 'No of Used'
            ]
        );


        try {
            $this->philHealthServices->ItemAdjustUpdate($this->E_ID, $this->E_YEAR, $this->E_NO_OF_USED, $this->E_NOTES);
            $this->E_ID = null;
            session()->flash('message', 'Successfully update');
        } catch (\Exception $e) {
            session()->flash('error', 'Error :' . $e->getMessage());
        }
    }

    #[On('open-philhealth-modifiy')]
    public function openModal()
    {
        $this->NO_OF_USED = 0;
        $this->NOTES = '';
        $this->YEAR = $this->dateServices->NowYear();
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {

        if ($this->showModal) {

            $this->dataList = $this->philHealthServices->ItemAdjustList($this->PATIENT_ID, $this->LOCATION_ID);
            $this->TOTAL = $this->PhicCount();
        }
        return view('livewire.patient.philhealth-modify');
    }
}
