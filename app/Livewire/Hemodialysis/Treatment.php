<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class Treatment extends Component
{
    public int $ID;
    public float $PRE_WEIGHT;
    public float $PRE_BLOOD_PRESSURE;
    public float $PRE_HEART_RATE;
    public float $PRE_O2_SATURATION;
    public float $PRE_TEMPERATURE;
    public float $POST_WEIGHT;
    public float $POST_BLOOD_PRESSURE;
    public float $POST_HEART_RATE;
    public float $POST_O2_SATURATION;
    public float $POST_TEMPERATURE;
    public string $UF_GOAL;
    public float $BFR;
    public float $DFR;
    public float $DURATION;
    public string $DIALYZER;
    public int $RE_USE_NO;
    public string $HERAPIN;
    public string $FLUSHING;

    public bool $SC_MACHINE_TEST;
    public bool $SC_SECURED_CONNECTION;
    public bool $SC_SALINE_LINE_DOUBLE_CLAMP;
    public float $SC_CONDUCTIVITY;
    public float $SC_DIALYATE_TEMP;
    public bool $SC_RESIDUAL_TEST_NIGATIVE;

    public bool $DB_STANDARD_HCOA;
    public bool $DB_ACID;
    public float $DB_NA_MEG_L;
    public float $DB_KPLUS_MEG_L;
    public float $DB_CAPPLS_MEG_L;


    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount($ID)
    {
        $this->ID = $ID;

        if ($this->ID > 0) {
            $data = $this->hemoServices->Get($this->ID);

            if ($data) {
                $this->PRE_WEIGHT = $data->PRE_WEIGHT ?? 0;
                $this->PRE_BLOOD_PRESSURE = $data->PRE_BLOOD_PRESSURE ?? 0;
                $this->PRE_HEART_RATE = $data->PRE_HEART_RATE ?? 0;
                $this->PRE_O2_SATURATION = $data->PRE_O2_SATURATION ?? 0;
                $this->PRE_TEMPERATURE = $data->PRE_TEMPERATURE ?? 0;
                $this->POST_WEIGHT = $data->POST_WEIGHT ?? 0;
                $this->POST_BLOOD_PRESSURE = $data->POST_BLOOD_PRESSURE ?? 0;
                $this->POST_HEART_RATE = $data->POST_HEART_RATE ?? 0;
                $this->POST_O2_SATURATION = $data->POST_O2_SATURATION ?? 0;
                $this->POST_TEMPERATURE = $data->POST_TEMPERATURE ?? 0;
                $this->UF_GOAL = $data->UF_GOAL ?? '';
                $this->BFR = $data->BFR ?? 0;
                $this->DFR = $data->DFR ?? 0;
                $this->DURATION = $data->DURATION ?? 0;
                $this->DIALYZER = $data->DIALYZER ?? '';
                $this->RE_USE_NO = $data->RE_USE_NO ?? 0;
                $this->HERAPIN = $data->HERAPIN ?? '';
                $this->FLUSHING = $data->FLUSHING ?? '';
                $this->SC_MACHINE_TEST = $data->SC_MACHINE_TEST;
                $this->SC_SECURED_CONNECTION = $data->SC_SECURED_CONNECTION;
                $this->SC_SALINE_LINE_DOUBLE_CLAMP = $data->SC_SALINE_LINE_DOUBLE_CLAMP;
                $this->SC_CONDUCTIVITY = $data->SC_CONDUCTIVITY ?? 0;
                $this->SC_DIALYATE_TEMP = $data->SC_DIALYATE_TEMP ?? 0;
                $this->SC_RESIDUAL_TEST_NIGATIVE = $data->SC_RESIDUAL_TEST_NIGATIVE;
                $this->DB_STANDARD_HCOA = $data->DB_STANDARD_HCOA;
                $this->DB_ACID = $data->DB_ACID;
                $this->DB_NA_MEG_L = $data->DB_NA_MEG_L ?? 0;
                $this->DB_KPLUS_MEG_L = $data->DB_KPLUS_MEG_L ?? 0;
                $this->DB_CAPPLS_MEG_L = $data->DB_CAPPLS_MEG_L ?? 0;

            }

        }
    }

    #[On('treatment-save')]
    public function save()
    {
        $object = [
            'DB_NA_MEG_L' => $this->DB_NA_MEG_L,
            'DB_KPLUS_MEG_L' => $this->DB_KPLUS_MEG_L,
            'DB_CAPPLS_MEG_L' => $this->DB_CAPPLS_MEG_L,
            'DB_ACID' => $this->DB_ACID,
            'DB_STANDARD_HCOA' => $this->DB_STANDARD_HCOA,
            'SC_RESIDUAL_TEST_NIGATIVE' => $this->SC_RESIDUAL_TEST_NIGATIVE,
            'SC_CONDUCTIVITY' => $this->SC_CONDUCTIVITY,
            'SC_DIALYATE_TEMP' => $this->SC_DIALYATE_TEMP,
            'SC_SALINE_LINE_DOUBLE_CLAMP' => $this->SC_SALINE_LINE_DOUBLE_CLAMP,
            'SC_MACHINE_TEST' => $this->SC_MACHINE_TEST,
            'SC_SECURED_CONNECTION' => $this->SC_SECURED_CONNECTION,
            'HERAPIN' => $this->HERAPIN,
            'FLUSHING' => $this->FLUSHING,
            'RE_USE_NO' => $this->RE_USE_NO,
            'DIALYZER' => $this->DIALYZER,
            'DURATION' => $this->DURATION,
            'DFR' => $this->DFR,
            'UF_GOAL' => $this->UF_GOAL,
            'BFR' => $this->BFR,
            'PRE_WEIGHT' => $this->PRE_WEIGHT,
            'POST_WEIGHT' => $this->POST_WEIGHT,
            'PRE_BLOOD_PRESSURE' => $this->PRE_BLOOD_PRESSURE,
            'POST_BLOOD_PRESSURE' => $this->POST_BLOOD_PRESSURE,
            'PRE_HEART_RATE' => $this->PRE_HEART_RATE,
            'POST_HEART_RATE' => $this->POST_HEART_RATE,
            'PRE_O2_SATURATION' => $this->PRE_O2_SATURATION,
            'POST_O2_SATURATION' => $this->POST_O2_SATURATION,
            'PRE_TEMPERATURE' => $this->PRE_TEMPERATURE,
            'POST_TEMPERATURE' => $this->POST_TEMPERATURE
        ];



        $this->hemoServices->Update($this->ID, $object);
       
        
    
    }


    public function render()
    {
        return view('livewire.hemodialysis.treatment');
    }
}
