<?php

namespace App\Livewire\Import;

use App\Services\AccountServices;
use App\Services\ContactServices;
use App\Services\PaymentMethodServices;
use App\Services\XeroDataServices;
use Illuminate\Support\Arr;
use Livewire\Attributes\On;
use Livewire\Component;

class XeroImportModal extends Component
{

    public int $CONTACT_ID;

    public $accountList = [];
    public $ACCOUNT_ID;
    public $dataList = [];
    public $contactList = [];
    public $paymentMethodList = [];
    public $DATE;
    public $SOURCE_TYPE;
    public $REFERENCE;
    public $locationid = 0;
    public bool $showModal = false;
    private $xeroDataServices;
    private $contactServices;
    private $paymentMethodServices;
    private $accountServices;
    public $DOC_TYPE = [];
    public int $DOC_ID;
    public string $DOC_NAME;
    public function boot(XeroDataServices $xero, ContactServices $contact, PaymentMethodServices $paymentMethodServices, AccountServices $accountServices)
    {
        $this->xeroDataServices = $xero;
        $this->contactServices = $contact;
        $this->paymentMethodServices = $paymentMethodServices;
        $this->accountServices = $accountServices;
    }

    #[On('dataSend')]
    public function openModal($dataSend)
    {
        $this->CONTACT_ID = 0;
        $this->ACCOUNT_ID = 0;
        $this->contactList = [];    
        $this->accountList = [];
        $this->DATE = $dataSend['DATE'];
        $this->SOURCE_TYPE = $dataSend['SOURCE_TYPE'];
        $this->REFERENCE = $dataSend['REFERENCE'];
        $this->locationid = $dataSend['locationid'];
        $this->dataList = $this->xeroDataServices->callReference(
            $this->REFERENCE,
            $this->DATE,
            $this->SOURCE_TYPE
        );

        foreach ($this->dataList as $data) {
            $this->DOC_TYPE = $this->xeroDataServices->DocumentType($data->SOURCE_TYPE);
            break;
        }
        $this->DOC_NAME = $this->DOC_TYPE['NAME'];
        $this->DOC_ID = (int) $this->DOC_TYPE['ID'];
        switch ($this->DOC_ID) {
            case 1:
                $this->contactList = $this->contactServices->getListAllType();
                break;
            case 2:
                $this->accountList = $this->accountServices->getBankAccount();
                $this->contactList = $this->contactServices->getListAllType();
                break;

            case 10;
                $this->contactList = $this->contactServices->getListAllType();
                break;
            case 11;
                $this->accountList = $this->accountServices->getBankAccount();
                $this->contactList = $this->contactServices->getListAllType();
                break;
            default:

        }




        $this->showModal = true;






    }
    public function save() {
        switch ($this->DOC_ID) {
            case 1: // bill
        


                break;
            
            default:
                # code...
                break;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.import.xero-import-modal');
    }
}
