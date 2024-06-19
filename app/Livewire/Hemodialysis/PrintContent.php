<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintContent extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $DATE;
    public $PHIC_NO;
    public string $FULL_NAME;
    public string $CODE;
    public string $DOB;
    public int $AGE = 0;
    private $hemoServices;
    private $contactServices;



    public string $OLD_PRE_WEIGHT;
    public string $OLD_PRE_BLOOD_PRESSURE;
    public string $OLD_PRE_BLOOD_PRESSURE2;
    public string $OLD_PRE_HEART_RATE;
    public string $OLD_PRE_O2_SATURATION;
    public string $OLD_PRE_TEMPERATURE;


    public string $OLD_POST_WEIGHT;
    public string $OLD_POST_BLOOD_PRESSURE;
    public string $OLD_POST_BLOOD_PRESSURE2;
    public string $OLD_POST_HEART_RATE;
    public string $OLD_POST_O2_SATURATION;
    public string $OLD_POST_TEMPERATURE;



    public int $CUSTOMER_ID;
    public int $LOCATION_ID;




    public function boot(HemoServices $hemoServices, ContactServices $contactServices)
    {
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
    }

    public function getPreviousTreatment()
    {
        $data = $this->hemoServices->GetLastTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);
        if ($data) {
            $this->OLD_PRE_WEIGHT = $data->PRE_WEIGHT ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE = $data->PRE_BLOOD_PRESSURE ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE2 = $data->PRE_BLOOD_PRESSURE2 ?? "";
            $this->OLD_PRE_HEART_RATE = $data->PRE_HEART_RATE ?? "";
            $this->OLD_PRE_O2_SATURATION = $data->PRE_O2_SATURATION ?? "";
            $this->OLD_PRE_TEMPERATURE = $data->PRE_TEMPERATURE ?? "";
            $this->OLD_POST_WEIGHT = $data->POST_WEIGHT ?? "";
            $this->OLD_POST_BLOOD_PRESSURE = $data->POST_BLOOD_PRESSURE ?? "";
            $this->OLD_POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
            $this->OLD_POST_HEART_RATE = $data->POST_HEART_RATE ?? "";
            $this->OLD_POST_O2_SATURATION = $data->POST_O2_SATURATION ?? "";
            $this->OLD_POST_TEMPERATURE = $data->POST_TEMPERATURE ?? "";
            return;
        }

        $this->OLD_PRE_WEIGHT = "";
        $this->OLD_PRE_BLOOD_PRESSURE = "";
        $this->OLD_PRE_BLOOD_PRESSURE2 = "";
        $this->OLD_PRE_HEART_RATE = "";
        $this->OLD_PRE_O2_SATURATION = "";
        $this->OLD_PRE_TEMPERATURE = "";
        $this->OLD_POST_WEIGHT = "";
        $this->OLD_POST_BLOOD_PRESSURE = "";
        $this->OLD_POST_BLOOD_PRESSURE2 = "";
        $this->OLD_POST_HEART_RATE = "";
        $this->OLD_POST_O2_SATURATION = "";
        $this->OLD_POST_TEMPERATURE = "";
    }
    public function mount()
    {
        $data = $this->hemoServices->GetFirst($this->HEMO_ID);
        if ($data) {
            $this->FULL_NAME = $data->CONTACT_NAME;
            $this->DATE = $data->DATE;
            $this->CODE = $data->CODE;
            $this->PHIC_NO = $data->PHIC_NO;
            $this->DOB = $data->DATE_OF_BIRTH;
            $this->AGE = $this->contactServices->calculateUserAge($this->DOB);
            $this->CUSTOMER_ID = $data->CUSTOMER_ID;
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->getPreviousTreatment();
        }
    }

    public array $collection = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];

    public function render()
    {


        return view('livewire.hemodialysis.print-content');
    }
}
