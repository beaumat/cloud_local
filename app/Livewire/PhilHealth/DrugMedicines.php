<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DrugMedicines extends Component
{

    public $dataList = [];
    public $ID = null;
    #[Reactive]
    public int $PHILHEALTH_ID;
    public string $GENERIC_NAME;
    public float $QUANTITY;
    public string $DOSSAGE;
    public string $ROUTE;
    public string $FREQUENCY;
    public float $TOTAL_COST;
    public string $CONT_GENERIC_NAME;
    public float $CONT_QUANTITY;
    public string $CONT_DOSSAGE;
    public string $CONT_ROUTE;
    public string $CONT_FREQUENCY;
    public float $CONT_TOTAL_COST;
    private $philHealthServices;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }
    public function mount()
    {
        $this->clearField();
        $this->canceled();
    }
    private function clearField()
    {
        $this->GENERIC_NAME = '';
        $this->QUANTITY = 0;
        $this->DOSSAGE = '';
        $this->ROUTE = '';
        $this->FREQUENCY = '';
        $this->TOTAL_COST = 0;
        $this->CONT_GENERIC_NAME = '';
        $this->CONT_QUANTITY = 0;
        $this->CONT_DOSSAGE = '';
        $this->CONT_ROUTE = '';
        $this->CONT_FREQUENCY = '';
        $this->CONT_TOTAL_COST = 0;
    }
    public function save()
    {
        $this->philHealthServices->DrugMedicineStore(
            $this->PHILHEALTH_ID,
            $this->GENERIC_NAME,
            $this->QUANTITY,
            $this->DOSSAGE,
            $this->ROUTE,
            $this->FREQUENCY,
            $this->TOTAL_COST,
            $this->CONT_GENERIC_NAME,
            $this->CONT_QUANTITY,
            $this->CONT_DOSSAGE,
            $this->CONT_ROUTE,
            $this->CONT_FREQUENCY,
            $this->CONT_TOTAL_COST
        );
        $this->clearField();
    }


    public string $E_GENERIC_NAME;
    public float $E_QUANTITY;
    public string $E_DOSSAGE;
    public string $E_ROUTE;
    public string $E_FREQUENCY;
    public float $E_TOTAL_COST;
    public string $E_CONT_GENERIC_NAME;
    public float $E_CONT_QUANTITY;
    public string $E_CONT_DOSSAGE;
    public string $E_CONT_ROUTE;
    public string $E_CONT_FREQUENCY;
    public float $E_CONT_TOTAL_COST;

    public function edit(int $ID)
    {
        $data = $this->philHealthServices->GetDrugMedicine($ID);
        if ($data) {
            $this->ID = $data->ID;
            $this->E_GENERIC_NAME = $data->GENERIC_NAME;
            $this->E_QUANTITY = $data->QUANTITY;
            $this->E_DOSSAGE = $data->DOSSAGE;
            $this->E_ROUTE = $data->ROUTE;
            $this->E_FREQUENCY = $data->FREQUENCY;
            $this->E_TOTAL_COST = $data->TOTAL_COST;
            $this->E_CONT_GENERIC_NAME = $data->CONT_GENERIC_NAME;
            $this->E_CONT_QUANTITY = $data->CONT_QUANTITY;
            $this->E_CONT_DOSSAGE = $data->CONT_DOSSAGE;
            $this->E_CONT_ROUTE = $data->CONT_ROUTE;
            $this->E_CONT_FREQUENCY = $data->CONT_FREQUENCY;
            $this->E_CONT_TOTAL_COST = $data->CONT_TOTAL_COST;
        }
    }
    public function canceled()
    {
        $this->ID = null;
        $this->E_GENERIC_NAME = '';
        $this->E_QUANTITY = 0;
        $this->E_DOSSAGE = '';
        $this->E_ROUTE = '';
        $this->E_FREQUENCY = '';
        $this->E_TOTAL_COST = 0;
        $this->E_CONT_GENERIC_NAME = '';
        $this->E_CONT_QUANTITY = 0;
        $this->E_CONT_DOSSAGE = '';
        $this->E_CONT_ROUTE = '';
        $this->E_CONT_FREQUENCY = '';
        $this->E_CONT_TOTAL_COST = 0;
    }
    public function update(
    ) {

        $this->philHealthServices->DrugMedicineUpdate(
            $this->ID,
            $this->PHILHEALTH_ID,
            $this->E_GENERIC_NAME,
            $this->E_QUANTITY,
            $this->E_DOSSAGE,
            $this->E_ROUTE,
            $this->E_FREQUENCY,
            $this->E_TOTAL_COST,
            $this->E_CONT_GENERIC_NAME,
            $this->E_CONT_QUANTITY,
            $this->E_CONT_DOSSAGE,
            $this->E_CONT_ROUTE,
            $this->E_CONT_FREQUENCY,
            $this->E_CONT_TOTAL_COST
        );
        $this->canceled();
    }

    public function delete(int $ID)
    {
        $this->philHealthServices->DrugMedicineDelete($ID);
        $this->clearField();
    }
    public function AutoFillUp() {
        
    }
    public function render()
    {
        $this->dataList = $this->philHealthServices->DrugMedicineList($this->PHILHEALTH_ID);
        return view('livewire.phil-health.drug-medicines');
    }
}
