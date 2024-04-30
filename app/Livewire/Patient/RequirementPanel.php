<?php

namespace App\Livewire\Patient;

use App\Services\ContactRequirementServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class RequirementPanel extends Component
{   
    #[Reactive]
    public int $CONTACT_ID;
    public $dataList = [];
    private $contactRequirementServices;
    public function boot(ContactRequirementServices $contactRequirementServices)
    {
        $this->contactRequirementServices = $contactRequirementServices;
    }  
    public function render()
    {

        $this->dataList = $this->contactRequirementServices->GetList($this->CONTACT_ID);

        return view('livewire.patient.requirement-panel');
    }
}
