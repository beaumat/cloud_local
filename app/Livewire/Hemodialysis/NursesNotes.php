<?php

namespace App\Livewire\Hemodialysis;

use App\Services\NursesNotesServices;
use Livewire\Component;

class NursesNotes extends Component
{
    public int $HEMO_ID;
    public string $TIME;
    public float $BP_1;
    public float $BP_2;
    public float $HR;
    public float $BFR;
    public float $AP;
    public float $VP;
    public float $TFR;
    public float $TMP;
    public float $HEPARIN;
    public float $FLUSHING;
    public string $NOTES;

    public $EDIT_ID = null;
    public string $EDIT_TIME;
    public float $EDIT_BP_1;
    public float $EDIT_BP_2;
    public float $EDIT_HR;
    public float $EDIT_BFR;
    public float $EDIT_AP;
    public float $EDIT_VP;
    public float $EDIT_TFR;
    public float $EDIT_TMP;
    public float $EDIT_HEPARIN;
    public float $EDIT_FLUSHING;
    public string $EDIT_NOTES;

    public $data = [];
    private $nursesNotesServices;
    public function boot(NursesNotesServices $nursesNotesServices)
    {
        $this->nursesNotesServices = $nursesNotesServices;
    }
    public function mount(int $ID)
    {
        $this->HEMO_ID = $ID;
        $this->reload();

    }
    public function reload()
    {
        $this->TIME = "";
        $this->BP_1 = 0;
        $this->BP_2 = 0;
        $this->HR = 0;
        $this->BFR = 0;
        $this->AP = 0;
        $this->VP = 0;
        $this->TFR = 0;
        $this->TMP = 0;
        $this->HEPARIN = 0;
        $this->FLUSHING = 0;
        $this->NOTES = '';
    }
    public function editData($ID, $TIME, $BP_1, $BP_2, $HR, $BFR, $AP, $VP, $TFR, $TMP, $HEPARIN, $FLUSHING, $NOTES)
    {
        $this->EDIT_ID = $ID;
        $this->EDIT_TIME = $TIME;
        $this->EDIT_BP_1 = $BP_1;
        $this->EDIT_BP_2 = $BP_2;
        $this->EDIT_HR = $HR;
        $this->EDIT_BFR = $BFR;
        $this->EDIT_AP = $AP;
        $this->EDIT_VP = $VP;
        $this->EDIT_TFR = $TFR;
        $this->EDIT_TMP = $TMP;
        $this->EDIT_HEPARIN = $HEPARIN;
        $this->EDIT_FLUSHING = $FLUSHING;
        $this->EDIT_NOTES = $NOTES;
    }
    public function updateData()
    {

        $this->validate(
            [
                'EDIT_TIME' => 'required',
            ],
            [],
            [
                'EDIT_TIME' => 'Time',
            ]
        );

        try {
            $this->nursesNotesServices->Update(
                $this->EDIT_ID,
                $this->EDIT_TIME,
                $this->EDIT_BP_1,
                $this->EDIT_BP_2,
                $this->EDIT_HR,
                $this->EDIT_BFR,
                $this->EDIT_AP,
                $this->EDIT_VP,
                $this->EDIT_TFR,
                $this->EDIT_TMP,
                $this->EDIT_HEPARIN,
                $this->EDIT_FLUSHING,
                $this->EDIT_NOTES
            );
    
            $this->EDIT_ID = null;
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
    public function cancel()
    {
        $this->EDIT_ID = null;
    }
    public function delete(int $ID)
    {  
        try {
            $this->nursesNotesServices->Delete($ID);
        } catch (\Exception $e) {
            //throw $th;
        }
    }
   
    public function save()
    {
        $this->validate(
            [
                'TIME' => 'required',

            ],
            [],
            [
                'TIME' => 'Time',

            ]
        );

        try {

            $this->nursesNotesServices->Save(
                $this->HEMO_ID,
                $this->TIME,
                $this->BP_1,
                $this->BP_2,
                $this->HR,
                $this->BFR,
                $this->AP,
                $this->VP,
                $this->TFR,
                $this->TMP,
                $this->HEPARIN,
                $this->FLUSHING,
                $this->NOTES
            );
            $this->reload();
            session()->flash('message', 'Successfuly added');

        } catch (\Exception $e) {
            dd($e->getMessage());
        }

    }
    public function render()
    {
        $this->data = $this->nursesNotesServices->List($this->HEMO_ID);
        return view('livewire.hemodialysis.nurses-notes');
    }
}
