<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Component;

class Treatment extends Component
{
    public int $ID;

    public float $PRE_WEIGHT = 0.0;
    public float $PRE_BLOOD_PRESSURE = 0.0;
    public float $PRE_HEART_RATE = 0.0;
    public float $PRE_O2_SATURATION = 0.0;
    public float $PRE_TEMPERATURE = 0.0;
    public float $POST_WEIGHT = 0.0;
    public float $POST_BLOOD_PRESSURE = 0.0;
    public float $POST_HEART_RATE = 0.0;
    public float $POST_O2_SATURATION = 0.0;
    public float $POST_TEMPERATURE = 0.0;

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

            }

        }
    }

    public function updatedPREWEIGHT()
    {
        $this->hemoServices->updateNumber($this->ID, 'PRE_WEIGHT', $this->PRE_WEIGHT);
    }
    public function updatedPOSTWEIGHT()
    {
        $this->hemoServices->updateNumber($this->ID, 'POST_WEIGHT', $this->POST_WEIGHT);
    }
    public function updatedPREBLOODPRESSURE()
    {
        $this->hemoServices->updateNumber($this->ID, 'PRE_BLOOD_PRESSURE', $this->PRE_BLOOD_PRESSURE);
    }
    public function updatedPOSTBLOODPRESSURE()
    {
        $this->hemoServices->updateNumber($this->ID, 'POST_BLOOD_PRESSURE', $this->POST_BLOOD_PRESSURE);
    }
    public function updatedPREHEARTRATE()
    {
        $this->hemoServices->updateNumber($this->ID, 'PRE_HEART_RATE', $this->PRE_HEART_RATE);
    }
    public function updatedPOSTHEARTRATE()
    {
        $this->hemoServices->updateNumber($this->ID, 'POST_HEART_RATE', $this->POST_HEART_RATE);
    }
    public function updatedPREO2SATURATION()
    {
        $this->hemoServices->updateNumber($this->ID, 'PRE_O2_SATURATION', $this->PRE_O2_SATURATION);
    }
    public function updatedPOSTO2SATURATION()
    {
        $this->hemoServices->updateNumber($this->ID, 'POST_O2_SATURATION', $this->POST_O2_SATURATION);
    }
    public function updatedPRETEMPERATURE()
    {
        $this->hemoServices->updateNumber($this->ID, 'PRE_TEMPERATURE', $this->PRE_TEMPERATURE);
    }
    public function updatedPOSTTEMPERATURE()
    {
        $this->hemoServices->updateNumber($this->ID, 'POST_TEMPERATURE', $this->POST_TEMPERATURE);
    }
    public function render()
    {
        return view('livewire.hemodialysis.treatment');
    }
}
