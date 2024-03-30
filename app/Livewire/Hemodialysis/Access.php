<?php

namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class Access extends Component
{
    public int $ID;
    public string $NEPHROLOGIST;
    public string $SPECIAL_ENDORSEMENT;
    public string $DIAGNOSIS;
    public string $STANDING_ORDER;
    public bool $ACCESS_TYPE_FISTUAL;
    public bool $ACCESS_TYPE_GRAFT;
    public bool $ACCESS_TYPE_R;
    public bool $ACCESS_TYPE_L;
    public bool $BRUIT_STRONG;
    public bool $BRUIT_WEAK;
    public bool $BRUIT_ABSENT;
    public bool $THRILL_STRONG;
    public bool $THRILL_WEAK;
    public bool $THRILL_ABSENT;
    public bool $HEMOTOMA_PRESENT;
    public bool $HEMOTOMA_ABSENT;
    public string $ACCESS_OTHERS;
    public bool $CVC_SUBCATH;
    public bool $CVC_JUGCATH;
    public bool $CVC_FEMCATH;
    public bool $CVC_PERMCATH;
    public bool $CVC_LOCATION_R;
    public bool $CVC_LOCATION_L;
    public bool $CVC_A_GOOD_FLOW;
    public bool $CVC_A_WRESISTANT;
    public bool $CVC_A_CLOTTED_NON_PATENT;
    public bool $CVC_v_GOOD_FLOW;
    public bool $CVC_v_WRESISTANT;
    public bool $CVC_v_CLOTTED_NON_PATENT;
    private $hemoServices;
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

                $this->NEPHROLOGIST = $data->NEPHROLOGIST ?? '';
                $this->SPECIAL_ENDORSEMENT = $data->SPECIAL_ENDORSEMENT ?? '';
                $this->DIAGNOSIS = $data->DIAGNOSIS ?? '';
                $this->STANDING_ORDER = $data->STANDING_ORDER ?? '';
                $this->ACCESS_TYPE_FISTUAL = $data->ACCESS_TYPE_FISTUAL;
                $this->ACCESS_TYPE_GRAFT = $data->ACCESS_TYPE_GRAFT;
                $this->ACCESS_TYPE_R = $data->ACCESS_TYPE_R;
                $this->ACCESS_TYPE_L = $data->ACCESS_TYPE_L;
                $this->BRUIT_STRONG = $data->BRUIT_STRONG;
                $this->BRUIT_WEAK = $data->BRUIT_WEAK;
                $this->BRUIT_ABSENT = $data->BRUIT_ABSENT;
                $this->THRILL_STRONG = $data->THRILL_STRONG;
                $this->THRILL_WEAK = $data->THRILL_WEAK;
                $this->THRILL_ABSENT = $data->THRILL_ABSENT;
                $this->HEMOTOMA_PRESENT = $data->HEMOTOMA_PRESENT;
                $this->HEMOTOMA_ABSENT = $data->HEMOTOMA_ABSENT;
                $this->ACCESS_OTHERS = $data->ACCESS_OTHERS ?? '';
                $this->CVC_SUBCATH = $data->CVC_SUBCATH;
                $this->CVC_JUGCATH = $data->CVC_JUGCATH;
                $this->CVC_FEMCATH = $data->CVC_FEMCATH;
                $this->CVC_PERMCATH = $data->CVC_PERMCATH;
                $this->CVC_LOCATION_R = $data->CVC_LOCATION_R;
                $this->CVC_LOCATION_L = $data->CVC_LOCATION_L;
                $this->CVC_A_GOOD_FLOW = $data->CVC_A_GOOD_FLOW;
                $this->CVC_A_WRESISTANT = $data->CVC_A_WRESISTANT;
                $this->CVC_A_CLOTTED_NON_PATENT = $data->CVC_A_CLOTTED_NON_PATENT;
                $this->CVC_v_GOOD_FLOW = $data->CVC_v_GOOD_FLOW;
                $this->CVC_v_WRESISTANT = $data->CVC_v_WRESISTANT;
                $this->CVC_v_CLOTTED_NON_PATENT = $data->CVC_v_CLOTTED_NON_PATENT;

            }
        }

    }
    #[On('access-save')]
    public function save()
    {
        $object = [
            'NEPHROLOGIST' => $this->NEPHROLOGIST,
            'SPECIAL_ENDORSEMENT' => $this->SPECIAL_ENDORSEMENT,
            'DIAGNOSIS' => $this->DIAGNOSIS,
            'STANDING_ORDER' => $this->STANDING_ORDER,
            'ACCESS_TYPE_FISTUAL' => $this->ACCESS_TYPE_FISTUAL,
            'ACCESS_TYPE_GRAFT' => $this->ACCESS_TYPE_GRAFT,
            'ACCESS_TYPE_R' => $this->ACCESS_TYPE_R,
            'ACCESS_TYPE_L' => $this->ACCESS_TYPE_L,
            'BRUIT_STRONG' => $this->BRUIT_STRONG,
            'BRUIT_WEAK' => $this->BRUIT_WEAK,
            'BRUIT_ABSENT' => $this->BRUIT_ABSENT,
            'THRILL_STRONG' => $this->THRILL_STRONG,
            'THRILL_WEAK' => $this->THRILL_WEAK,
            'THRILL_ABSENT' => $this->THRILL_ABSENT,
            'HEMOTOMA_PRESENT' => $this->HEMOTOMA_PRESENT,
            'HEMOTOMA_ABSENT' => $this->HEMOTOMA_ABSENT,
            'ACCESS_OTHERS' => $this->ACCESS_OTHERS,
            'CVC_SUBCATH' => $this->CVC_SUBCATH,
            'CVC_JUGCATH' => $this->CVC_JUGCATH,
            'CVC_FEMCATH' => $this->CVC_FEMCATH,
            'CVC_PERMCATH' => $this->CVC_PERMCATH,
            'CVC_LOCATION_R' => $this->CVC_LOCATION_R,
            'CVC_LOCATION_L' => $this->CVC_LOCATION_L,
            'CVC_A_GOOD_FLOW' => $this->CVC_A_GOOD_FLOW,
            'CVC_A_WRESISTANT' => $this->CVC_A_WRESISTANT,
            'CVC_A_CLOTTED_NON_PATENT' => $this->CVC_A_CLOTTED_NON_PATENT,
            'CVC_v_GOOD_FLOW' => $this->CVC_v_GOOD_FLOW,
            'CVC_v_WRESISTANT' => $this->CVC_v_WRESISTANT,
            'CVC_v_CLOTTED_NON_PATENT' => $this->CVC_v_CLOTTED_NON_PATENT
        ];

        $this->hemoServices->Update($this->ID, $object);
    }

    public function render()
    {
        return view('livewire.hemodialysis.access');
    }
}
