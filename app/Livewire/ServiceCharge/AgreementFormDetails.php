<?php

namespace App\Livewire\ServiceCharge;

use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormDetails extends Component
{   
    #[Reactive]
    public $HEMO_ID;

    public $dataList = [];
    public $titleSelected = [];
    public $checkedItems = [];
    private $phicAgreementFormServices;
    public function boot(PhicAgreementFormServices $phicAgreementFormServices)
    {
        $this->phicAgreementFormServices = $phicAgreementFormServices;
    }

    private function getList()
    {
        if ($this->HEMO_ID > 0) {
            $data = $this->phicAgreementFormServices->getList($this->HEMO_ID);

            foreach ($data as $list) {
                $this->checkedItems[$list->ID] = (bool) $list->IS_CHECK;
            }
            $this->dataList = $data;
        }

    }
    public function update(int $PHIC_AFT_ID, bool $STATUS)
    {

        if ($this->phicAgreementFormServices->isExist($this->HEMO_ID, $PHIC_AFT_ID)) {
            // Update
            $this->phicAgreementFormServices->updateDetails($this->HEMO_ID, $PHIC_AFT_ID, $STATUS);
        } else {
            // INSERT
            $this->phicAgreementFormServices->storeDetails($this->HEMO_ID, $PHIC_AFT_ID, $STATUS);
        }
    }
    public function render()
    {   

        $this->getList();

        
        return view('livewire.service-charge.agreement-form-details');
    }
}
