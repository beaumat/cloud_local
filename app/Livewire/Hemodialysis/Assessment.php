<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class Assessment extends Component
{
    public int $ID;
    private $hemoServices;
    public bool $PRE_ASSIST_AMBULATORY;
    public bool $PRE_ASSIST_AMBULATORY_W_ASSIST;
    public bool $PRE_ASSIST_WHEEL_CHAIR;
    public bool $PRE_ASSIST_CLEAR;
    public bool $PRE_ASSIST_CRACKLES;
    public bool $PRE_ASSIST_RHONCHI;
    public bool $PRE_ASSIST_WHEEZES;
    public bool $PRE_ASSIST_RALES;
    public bool $PRE_ASSIST_DISTENDID_JUGULAR_VIEW;
    public bool $PRE_ASSIST_ASCITES;
    public bool $PRE_ASSIST_EDEMA;
    public string $PRE_ASSIST_EDEMA_LOCAITON;
    public string $PRE_ASSIST_EDEMA_DEPTH;
    public bool $PRE_ASSSIT_REGULAR;
    public bool $PRE_ASSIST_IRREGULAR;
    public bool $PRE_ASSIST_CONSCIOUS;
    public bool $PRE_ASSIST_COHERENT;
    public bool $PRE_ASSIST_DISORIENTED;
    public bool $PRE_ASSIST_DROWSY;
    public bool $POST_ASSIST_AMBULATORY;
    public bool $POST_ASSIST_AMBULATORY_W_ASSIST;
    public bool $POST_ASSIST_WHEEL_CHAIR;
    public bool $POST_ASSIST_CLEAR;
    public bool $POST_ASSIST_CRACKLES;
    public bool $POST_ASSIST_RHONCHI;
    public bool $POST_ASSIST_WHEEZES;
    public bool $POST_ASSIST_RALES;
    public bool $POST_ASSIST_DISTENDID_JUGULAR_VIEW;
    public bool $POST_ASSIST_ASCITES;
    public bool $POST_ASSIST_EDEMA;
    public string $POST_ASSIST_EDEMA_LOCAITON;
    public string $POST_ASSIST_EDEMA_DEPTH;
    public bool $POST_ASSSIT_REGULAR;
    public bool $POST_ASSIST_IRREGULAR;
    public bool $POST_ASSIST_CONSCIOUS;
    public bool $POST_ASSIST_COHERENT;
    public bool $POST_ASSIST_DISORIENTED;
    public bool $POST_ASSIST_DROWSY;

    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount(int $ID)
    {
        $this->ID = $ID;

        if ($ID > 0) {
            $data = $this->hemoServices->Get($ID);
            if ($data) {
                $this->PRE_ASSIST_AMBULATORY = $data->PRE_ASSIST_AMBULATORY;
                $this->PRE_ASSIST_AMBULATORY_W_ASSIST = $data->PRE_ASSIST_AMBULATORY_W_ASSIST;
                $this->PRE_ASSIST_WHEEL_CHAIR = $data->PRE_ASSIST_WHEEL_CHAIR;
                $this->PRE_ASSIST_CLEAR = $data->PRE_ASSIST_CLEAR;
                $this->PRE_ASSIST_CRACKLES = $data->PRE_ASSIST_CRACKLES;
                $this->PRE_ASSIST_RHONCHI = $data->PRE_ASSIST_RHONCHI;
                $this->PRE_ASSIST_WHEEZES = $data->PRE_ASSIST_WHEEZES;
                $this->PRE_ASSIST_RALES = $data->PRE_ASSIST_RALES;
                $this->PRE_ASSIST_DISTENDID_JUGULAR_VIEW = $data->PRE_ASSIST_DISTENDID_JUGULAR_VIEW;
                $this->PRE_ASSIST_ASCITES = $data->PRE_ASSIST_ASCITES;
                $this->PRE_ASSIST_EDEMA = $data->PRE_ASSIST_EDEMA;
                $this->PRE_ASSIST_EDEMA_LOCAITON = $data->PRE_ASSIST_EDEMA_LOCAITON ?? '';
                $this->PRE_ASSIST_EDEMA_DEPTH = $data->PRE_ASSIST_EDEMA_DEPTH ?? '';
                $this->PRE_ASSSIT_REGULAR = $data->PRE_ASSSIT_REGULAR;
                $this->PRE_ASSIST_IRREGULAR = $data->PRE_ASSIST_IRREGULAR;
                $this->PRE_ASSIST_CONSCIOUS = $data->PRE_ASSIST_CONSCIOUS;
                $this->PRE_ASSIST_COHERENT = $data->PRE_ASSIST_COHERENT;
                $this->PRE_ASSIST_DISORIENTED = $data->PRE_ASSIST_DISORIENTED;
                $this->PRE_ASSIST_DROWSY = $data->PRE_ASSIST_DROWSY;
                $this->POST_ASSIST_AMBULATORY = $data->POST_ASSIST_AMBULATORY;
                $this->POST_ASSIST_AMBULATORY_W_ASSIST = $data->POST_ASSIST_AMBULATORY_W_ASSIST;
                $this->POST_ASSIST_WHEEL_CHAIR = $data->POST_ASSIST_WHEEL_CHAIR;
                $this->POST_ASSIST_CLEAR = $data->POST_ASSIST_CLEAR;
                $this->POST_ASSIST_CRACKLES = $data->POST_ASSIST_CRACKLES;
                $this->POST_ASSIST_RHONCHI = $data->POST_ASSIST_RHONCHI;
                $this->POST_ASSIST_WHEEZES = $data->POST_ASSIST_WHEEZES;
                $this->POST_ASSIST_RALES = $data->POST_ASSIST_RALES;
                $this->POST_ASSIST_DISTENDID_JUGULAR_VIEW = $data->POST_ASSIST_DISTENDID_JUGULAR_VIEW;
                $this->POST_ASSIST_ASCITES = $data->POST_ASSIST_ASCITES;
                $this->POST_ASSIST_EDEMA = $data->POST_ASSIST_EDEMA;
                $this->POST_ASSIST_EDEMA_LOCAITON = $data->POST_ASSIST_EDEMA_LOCAITON ?? '';
                $this->POST_ASSIST_EDEMA_DEPTH = $data->POST_ASSIST_EDEMA_DEPTH ?? '';
                $this->POST_ASSSIT_REGULAR = $data->POST_ASSSIT_REGULAR;
                $this->POST_ASSIST_IRREGULAR = $data->POST_ASSIST_IRREGULAR;
                $this->POST_ASSIST_CONSCIOUS = $data->POST_ASSIST_CONSCIOUS;
                $this->POST_ASSIST_COHERENT = $data->POST_ASSIST_COHERENT;
                $this->POST_ASSIST_DISORIENTED = $data->POST_ASSIST_DISORIENTED;
                $this->POST_ASSIST_DROWSY = $data->POST_ASSIST_DROWSY;
            }
        }
    }
    #[On('assessment-save')]
    public function save()
    {
        $object = [
            'PRE_ASSIST_AMBULATORY' => $this->PRE_ASSIST_AMBULATORY,
            'PRE_ASSIST_AMBULATORY_W_ASSIST' => $this->PRE_ASSIST_AMBULATORY_W_ASSIST,
            'PRE_ASSIST_WHEEL_CHAIR' => $this->PRE_ASSIST_WHEEL_CHAIR,
            'PRE_ASSIST_CLEAR' => $this->PRE_ASSIST_CLEAR,
            'PRE_ASSIST_CRACKLES' => $this->PRE_ASSIST_CRACKLES,
            'PRE_ASSIST_RHONCHI' => $this->PRE_ASSIST_RHONCHI,
            'PRE_ASSIST_WHEEZES' => $this->PRE_ASSIST_WHEEZES,
            'PRE_ASSIST_RALES' => $this->PRE_ASSIST_RALES,
            'PRE_ASSIST_DISTENDID_JUGULAR_VIEW' => $this->PRE_ASSIST_DISTENDID_JUGULAR_VIEW,
            'PRE_ASSIST_ASCITES' => $this->PRE_ASSIST_ASCITES,
            'PRE_ASSIST_EDEMA' => $this->PRE_ASSIST_EDEMA,
            'PRE_ASSIST_EDEMA_LOCAITON' => $this->PRE_ASSIST_EDEMA_LOCAITON,
            'PRE_ASSIST_EDEMA_DEPTH' => $this->PRE_ASSIST_EDEMA_DEPTH,
            'PRE_ASSSIT_REGULAR' => $this->PRE_ASSSIT_REGULAR,
            'PRE_ASSIST_IRREGULAR' => $this->PRE_ASSIST_IRREGULAR,
            'PRE_ASSIST_CONSCIOUS' => $this->PRE_ASSIST_CONSCIOUS,
            'PRE_ASSIST_COHERENT' => $this->PRE_ASSIST_COHERENT,
            'PRE_ASSIST_DISORIENTED' => $this->PRE_ASSIST_DISORIENTED,
            'PRE_ASSIST_DROWSY' => $this->PRE_ASSIST_DROWSY,
            'POST_ASSIST_AMBULATORY' => $this->POST_ASSIST_AMBULATORY,
            'POST_ASSIST_AMBULATORY_W_ASSIST' => $this->POST_ASSIST_AMBULATORY_W_ASSIST,
            'POST_ASSIST_WHEEL_CHAIR' => $this->POST_ASSIST_WHEEL_CHAIR,
            'POST_ASSIST_CLEAR' => $this->POST_ASSIST_CLEAR,
            'POST_ASSIST_CRACKLES' => $this->POST_ASSIST_CRACKLES,
            'POST_ASSIST_RHONCHI' => $this->POST_ASSIST_RHONCHI,
            'POST_ASSIST_WHEEZES' => $this->POST_ASSIST_WHEEZES,
            'POST_ASSIST_RALES' => $this->POST_ASSIST_RALES,
            'POST_ASSIST_DISTENDID_JUGULAR_VIEW' => $this->POST_ASSIST_DISTENDID_JUGULAR_VIEW,
            'POST_ASSIST_ASCITES' => $this->POST_ASSIST_ASCITES,
            'POST_ASSIST_EDEMA' => $this->POST_ASSIST_EDEMA,
            'POST_ASSIST_EDEMA_LOCAITON' => $this->POST_ASSIST_EDEMA_LOCAITON,
            'POST_ASSIST_EDEMA_DEPTH' => $this->POST_ASSIST_EDEMA_DEPTH,
            'POST_ASSSIT_REGULAR' => $this->POST_ASSSIT_REGULAR,
            'POST_ASSIST_IRREGULAR' => $this->POST_ASSIST_IRREGULAR,
            'POST_ASSIST_CONSCIOUS' => $this->POST_ASSIST_CONSCIOUS,
            'POST_ASSIST_COHERENT' => $this->POST_ASSIST_COHERENT,
            'POST_ASSIST_DISORIENTED' => $this->POST_ASSIST_DISORIENTED,
            'POST_ASSIST_DROWSY' => $this->POST_ASSIST_DROWSY

        ];
        $this->hemoServices->Update($this->ID, $object);
    }
    public function render()
    {
        return view('livewire.hemodialysis.assessment');
    }
}
