<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthServices;
use App\Services\UserServices;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;
use Redirect;

#[Title('Philhealth')]

class PhilHealthForm extends Component
{

    public bool $Modify = false;
    public string $STATUS_DESCRIPTION;
    public int $STATUS;
    public int $ID = 0;
    public string $tab = "treatment";
    public $locationList = [];
    public $patientList = [];
    public int $LOCATION_ID = 9;
    public int $CONTACT_ID = 0;
    public string $CODE;
    public $DATE;
    public $DATE_ADMITTED;
    public $TIME_ADMITTED;
    public $DATE_DISCHARGED;
    public $TIME_DISCHARGED;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public int $STATUS_ID;
    public float $CHARGES_ROOM_N_BOARD;
    public float $CHARGES_DRUG_N_MEDICINE;
    public float $CHARGES_LAB_N_DIAGNOSTICS;
    public float $CHARGES_OPERATING_ROOM_FEE;
    public float $CHARGES_SUPPLIES;
    public float $CHARGES_OTHERS;
    public float $CHARGES_SUB_TOTAL;
    public string $OTHER_SPECIFY;
    public float $VAT_ROOM_N_BOARD;
    public float $VAT_DRUG_N_MEDICINE;
    public float $VAT_LAB_N_DIAGNOSTICS;
    public float $VAT_OPERATING_ROOM_FEE;
    public float $VAT_SUPPLIES;
    public float $VAT_OTHERS;
    public float $VAT_SUB_TOTAL;
    public float $SP_ROOM_N_BOARD;
    public float $SP_DRUG_N_MEDICINE;
    public float $SP_LAB_N_DIAGNOSTICS;
    public float $SP_OPERATING_ROOM_FEE;
    public float $SP_SUPPLIES;
    public float $SP_OTHERS;
    public float $SP_SUB_TOTAL;
    public float $GOV_ROOM_N_BOARD;
    public float $GOV_DRUG_N_MEDICINE;
    public float $GOV_LAB_N_DIAGNOSTICS;
    public float $GOV_OPERATING_ROOM_FEE;
    public float $GOV_SUPPLIES;
    public float $GOV_OTHERS;
    public float $GOV_SUB_TOTAL;
    public bool $GOV_PCSO;
    public bool $GOV_DSWD;
    public bool $GOV_DOH;
    public bool $GOV_HMO;
    public bool $GOV_LINGAP;
    public float $P1_ROOM_N_BOARD;
    public float $P1_DRUG_N_MEDICINE;
    public float $P1_LAB_N_DIAGNOSTICS;
    public float $P1_OPERATING_ROOM_FEE;
    public float $P1_SUPPLIES;
    public float $P1_OTHERS;
    public float $P1_SUB_TOTAL;
    public float $P2_ROOM_N_BOARD;
    public float $P2_DRUG_N_MEDICINE;
    public float $P2_LAB_N_DIAGNOSTICS;
    public float $P2_OPERATING_ROOM_FEE;
    public float $P2_SUPPLIES;
    public float $P2_OTHERS;
    public float $P2_SUB_TOTAL;
    public float $OP_ROOM_N_BOARD;
    public float $OP_DRUG_N_MEDICINE;
    public float $OP_LAB_N_DIAGNOSTICS;
    public float $OP_OPERATING_ROOM_FEE;
    public float $OP_SUPPLIES;
    public float $OP_OTHERS;
    public float $OP_SUB_TOTAL;
    public float $PROFESSIONAL_FEE_SUB_TOTAL;
    public float $CHARGE_TOTAL;
    public float $VAT_TOTAL;
    public float $SP_TOTAL;
    public float $GOV_TOTAL;
    public float $P1_TOTAL;
    public float $P2_TOTAL;
    public float $OP_TOTAL;
    public int $PREPARED_BY_ID;
    public string $DATE_SIGNED;
    public string $OTHER_NAME;
    private $philHealthServices;
    private $hemoServices;
    private $contactServices;
    private $locationServices;
    private $userServices;
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }

    public function boot(
        PhilHealthServices $philHealthServices,
        HemoServices $hemoServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->hemoServices = $hemoServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount($id = null)
    {
        $this->locationList = $this->locationServices->getList();
        $this->patientList = $this->contactServices->getList(3);

        if (is_numeric($id)) {
            $data = $this->philHealthServices->get($id);

            if ($data) {
                $this->ID = $data->ID;
                $this->CODE = $data->CODE;
                $this->DATE = $data->DATE;
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->CONTACT_ID = $data->CONTACT_ID;
                $this->DATE_ADMITTED = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED = $data->TIME_DISCHARGED;
                $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS;
                $this->OTHER_DIAGNOSIS = $data->OTHER_DIAGNOSIS;
                $this->FIRST_CASE_RATE = $data->FIRST_CASE_RATE;
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE;
                $this->CHARGES_ROOM_N_BOARD = $data->CHARGES_ROOM_N_BOARD;
                $this->CHARGES_DRUG_N_MEDICINE = $data->CHARGES_DRUG_N_MEDICINE;
                $this->CHARGES_LAB_N_DIAGNOSTICS = $data->CHARGES_LAB_N_DIAGNOSTICS;
                $this->CHARGES_OPERATING_ROOM_FEE = $data->CHARGES_OPERATING_ROOM_FEE;
                $this->CHARGES_SUPPLIES = $data->CHARGES_SUPPLIES;
                $this->CHARGES_OTHERS = $data->CHARGES_OTHERS;
                $this->CHARGES_SUB_TOTAL = $data->CHARGES_SUB_TOTAL;
                $this->OTHER_SPECIFY = $data->OTHER_SPECIFY;
                $this->VAT_ROOM_N_BOARD = $data->VAT_ROOM_N_BOARD;
                $this->VAT_DRUG_N_MEDICINE = $data->VAT_DRUG_N_MEDICINE;
                $this->VAT_LAB_N_DIAGNOSTICS = $data->VAT_LAB_N_DIAGNOSTICS;
                $this->VAT_OPERATING_ROOM_FEE = $data->VAT_OPERATING_ROOM_FEE;
                $this->VAT_SUPPLIES = $data->VAT_SUPPLIES;
                $this->VAT_OTHERS = $data->VAT_OTHERS;
                $this->VAT_SUB_TOTAL = $data->VAT_SUB_TOTAL;
                $this->SP_ROOM_N_BOARD = $data->SP_ROOM_N_BOARD;
                $this->SP_DRUG_N_MEDICINE = $data->SP_DRUG_N_MEDICINE;
                $this->SP_LAB_N_DIAGNOSTICS = $data->SP_LAB_N_DIAGNOSTICS;
                $this->SP_OPERATING_ROOM_FEE = $data->SP_OPERATING_ROOM_FEE;
                $this->SP_SUPPLIES = $data->SP_SUPPLIES;
                $this->SP_OTHERS = $data->SP_OTHERS;
                $this->SP_SUB_TOTAL = $data->SP_SUB_TOTAL;
                $this->GOV_ROOM_N_BOARD = $data->GOV_ROOM_N_BOARD;
                $this->GOV_DRUG_N_MEDICINE = $data->GOV_DRUG_N_MEDICINE;
                $this->GOV_LAB_N_DIAGNOSTICS = $data->GOV_LAB_N_DIAGNOSTICS;
                $this->GOV_OPERATING_ROOM_FEE = $data->GOV_OPERATING_ROOM_FEE;
                $this->GOV_SUPPLIES = $data->GOV_SUPPLIES;
                $this->GOV_OTHERS = $data->GOV_OTHERS;
                $this->GOV_SUB_TOTAL = $data->GOV_SUB_TOTAL;
                $this->GOV_PCSO = $data->GOV_PCSO;
                $this->GOV_DSWD = $data->GOV_DSWD;
                $this->GOV_DOH = $data->GOV_DOH;
                $this->GOV_HMO = $data->GOV_HMO;
                $this->GOV_LINGAP = $data->GOV_LINGAP;
                $this->P1_ROOM_N_BOARD = $data->P1_ROOM_N_BOARD;
                $this->P1_DRUG_N_MEDICINE = $data->P1_DRUG_N_MEDICINE;
                $this->P1_LAB_N_DIAGNOSTICS = $data->P1_LAB_N_DIAGNOSTICS;
                $this->P1_OPERATING_ROOM_FEE = $data->P1_OPERATING_ROOM_FEE;
                $this->P1_SUPPLIES = $data->P1_SUPPLIES;
                $this->P1_OTHERS = $data->P1_OTHERS;
                $this->P1_SUB_TOTAL = $data->P1_SUB_TOTAL;
                $this->P2_ROOM_N_BOARD = $data->P2_ROOM_N_BOARD;
                $this->P2_DRUG_N_MEDICINE = $data->P2_DRUG_N_MEDICINE;
                $this->P2_LAB_N_DIAGNOSTICS = $data->P2_LAB_N_DIAGNOSTICS;
                $this->P2_OPERATING_ROOM_FEE = $data->P2_OPERATING_ROOM_FEE;
                $this->P2_SUPPLIES = $data->P2_SUPPLIES;
                $this->P2_OTHERS = $data->P2_OTHERS;
                $this->P2_SUB_TOTAL = $data->P2_SUB_TOTAL;
                $this->OP_ROOM_N_BOARD = $data->OP_ROOM_N_BOARD;
                $this->OP_DRUG_N_MEDICINE = $data->OP_DRUG_N_MEDICINE;
                $this->OP_LAB_N_DIAGNOSTICS = $data->OP_LAB_N_DIAGNOSTICS;
                $this->OP_OPERATING_ROOM_FEE = $data->OP_OPERATING_ROOM_FEE;
                $this->OP_SUPPLIES = $data->OP_SUPPLIES;
                $this->OP_OTHERS = $data->OP_OTHERS;
                $this->OP_SUB_TOTAL = $data->OP_SUB_TOTAL;
                $this->PROFESSIONAL_FEE_SUB_TOTAL = $data->PROFESSIONAL_FEE_SUB_TOTAL;
                $this->CHARGE_TOTAL = $data->CHARGE_TOTAL;
                $this->VAT_TOTAL = $data->VAT_TOTAL;
                $this->SP_TOTAL = $data->SP_TOTAL;
                $this->GOV_TOTAL = $data->GOV_TOTAL;
                $this->P1_TOTAL = $data->P1_TOTAL;
                $this->P2_TOTAL = $data->P2_TOTAL;
                $this->OP_TOTAL = $data->OP_TOTAL;
                $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
                $this->DATE_SIGNED = $data->DATE_SIGNED ?? '';
                $this->OTHER_NAME = $data->OTHER_NAME;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('transactionsphic')->with('error', $errorMessage);

        }





        $this->ID = 0;
        $this->CODE = '';
        $this->DATE = Carbon::now()->format('Y-m-d');
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->CONTACT_ID = 0;
        $this->DATE_ADMITTED = null;
        $this->TIME_ADMITTED = null;
        $this->DATE_DISCHARGED = null;
        $this->TIME_ADMITTED = null;
        $this->FINAL_DIAGNOSIS = '';
        $this->OTHER_DIAGNOSIS = '';
        $this->FIRST_CASE_RATE = '';
        $this->SECOND_CASE_RATE = '';
        $this->CHARGES_ROOM_N_BOARD = 0;
        $this->CHARGES_DRUG_N_MEDICINE = 0;
        $this->CHARGES_LAB_N_DIAGNOSTICS = 0;
        $this->CHARGES_OPERATING_ROOM_FEE = 0;
        $this->CHARGES_SUPPLIES = 0;
        $this->CHARGES_OTHERS = 0;
        $this->CHARGES_SUB_TOTAL = 0;
        $this->OTHER_SPECIFY = '';
        $this->VAT_ROOM_N_BOARD = 0;
        $this->VAT_DRUG_N_MEDICINE = 0;
        $this->VAT_LAB_N_DIAGNOSTICS = 0;
        $this->VAT_OPERATING_ROOM_FEE = 0;
        $this->VAT_SUPPLIES = 0;
        $this->VAT_OTHERS = 0;
        $this->VAT_SUB_TOTAL = 0;
        $this->SP_ROOM_N_BOARD = 0;
        $this->SP_DRUG_N_MEDICINE = 0;
        $this->SP_LAB_N_DIAGNOSTICS = 0;
        $this->SP_OPERATING_ROOM_FEE = 0;
        $this->SP_SUPPLIES = 0;
        $this->SP_OTHERS = 0;
        $this->SP_SUB_TOTAL = 0;
        $this->GOV_ROOM_N_BOARD = 0;
        $this->GOV_DRUG_N_MEDICINE = 0;
        $this->GOV_LAB_N_DIAGNOSTICS = 0;
        $this->GOV_OPERATING_ROOM_FEE = 0;
        $this->GOV_SUPPLIES = 0;
        $this->GOV_OTHERS = 0;
        $this->GOV_SUB_TOTAL = 0;
        $this->GOV_PCSO = false;
        $this->GOV_DSWD = false;
        $this->GOV_DOH = false;
        $this->GOV_HMO = false;
        $this->GOV_LINGAP = false;
        $this->P1_ROOM_N_BOARD = 0;
        $this->P1_DRUG_N_MEDICINE = 0;
        $this->P1_LAB_N_DIAGNOSTICS = 0;
        $this->P1_OPERATING_ROOM_FEE = 0;
        $this->P1_SUPPLIES = 0;
        $this->P1_OTHERS = 0;
        $this->P1_SUB_TOTAL = 0;
        $this->P2_ROOM_N_BOARD = 0;
        $this->P2_DRUG_N_MEDICINE = 0;
        $this->P2_LAB_N_DIAGNOSTICS = 0;
        $this->P2_OPERATING_ROOM_FEE = 0;
        $this->P2_SUPPLIES = 0;
        $this->P2_OTHERS = 0;
        $this->P2_SUB_TOTAL = 0;
        $this->OP_ROOM_N_BOARD = 0;
        $this->OP_DRUG_N_MEDICINE = 0;
        $this->OP_LAB_N_DIAGNOSTICS = 0;
        $this->OP_OPERATING_ROOM_FEE = 0;
        $this->OP_SUPPLIES = 0;
        $this->OP_OTHERS = 0;
        $this->OP_SUB_TOTAL = 0;
        $this->PROFESSIONAL_FEE_SUB_TOTAL = 0;
        $this->CHARGE_TOTAL = 0;
        $this->VAT_TOTAL = 0;
        $this->SP_TOTAL = 0;
        $this->GOV_TOTAL = 0;
        $this->P1_TOTAL = 0;
        $this->P2_TOTAL = 0;
        $this->OP_TOTAL = 0;
        $this->PREPARED_BY_ID = 0;
        $this->DATE_SIGNED = '';
        $this->OTHER_NAME = '';


        $this->Modify = true;

    }

    public function save()
    {

        $this->validate(
            [
                'CONTACT_ID' => 'required|not_in:0',
                'DATE' => 'required',
                'LOCATION_ID' => 'required',
                'DATE_ADMITTED' => 'required',
                'TIME_ADMITTED' => 'required',
                'DATE_DISCHARGED' => 'required',
                'TIME_DISCHARGED' => 'required'
            ],
            [],
            [
                'CONTACT_ID' => 'Patient',
                'DATE' => 'Date',
                'LOCATION_ID' => 'Location',
                'DATE_ADMITTED' => 'Date Admitted',
                'TIME_ADMITTED' => 'Time Admiited',
                'DATE_DISCHARGED' => 'Date Discharged',
                'TIME_DISCHARGED' => 'Time Discharged'
            ]
        );

        if ($this->ID == 0) {

            $this->philHealthServices->Store(
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_ADMITTED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE,
                $this->CHARGES_ROOM_N_BOARD,
                $this->CHARGES_DRUG_N_MEDICINE,
                $this->CHARGES_LAB_N_DIAGNOSTICS,
                $this->CHARGES_OPERATING_ROOM_FEE,
                $this->CHARGES_SUPPLIES,
                $this->CHARGES_OTHERS,
                $this->CHARGES_SUB_TOTAL,
                $this->OTHER_SPECIFY,
                $this->VAT_ROOM_N_BOARD,
                $this->VAT_DRUG_N_MEDICINE,
                $this->VAT_LAB_N_DIAGNOSTICS,
                $this->VAT_OPERATING_ROOM_FEE,
                $this->VAT_SUPPLIES,
                $this->VAT_OTHERS,
                $this->VAT_SUB_TOTAL,
                $this->SP_ROOM_N_BOARD,
                $this->SP_DRUG_N_MEDICINE,
                $this->SP_LAB_N_DIAGNOSTICS,
                $this->SP_OPERATING_ROOM_FEE,
                $this->SP_SUPPLIES,
                $this->SP_OTHERS,
                $this->SP_SUB_TOTAL,
                $this->GOV_ROOM_N_BOARD,
                $this->GOV_DRUG_N_MEDICINE,
                $this->GOV_LAB_N_DIAGNOSTICS,
                $this->GOV_OPERATING_ROOM_FEE,
                $this->GOV_SUPPLIES,
                $this->GOV_OTHERS,
                $this->GOV_SUB_TOTAL,
                $this->GOV_PCSO,
                $this->GOV_DSWD,
                $this->GOV_DOH,
                $this->GOV_HMO,
                $this->GOV_LINGAP,
                $this->P1_ROOM_N_BOARD,
                $this->P1_DRUG_N_MEDICINE,
                $this->P1_LAB_N_DIAGNOSTICS,
                $this->P1_OPERATING_ROOM_FEE,
                $this->P1_SUPPLIES,
                $this->P1_OTHERS,
                $this->P1_SUB_TOTAL,
                $this->P2_ROOM_N_BOARD,
                $this->P2_DRUG_N_MEDICINE,
                $this->P2_LAB_N_DIAGNOSTICS,
                $this->P2_OPERATING_ROOM_FEE,
                $this->P2_SUPPLIES,
                $this->P2_OTHERS,
                $this->P2_SUB_TOTAL,
                $this->OP_ROOM_N_BOARD,
                $this->OP_DRUG_N_MEDICINE,
                $this->OP_LAB_N_DIAGNOSTICS,
                $this->OP_OPERATING_ROOM_FEE,
                $this->OP_SUPPLIES,
                $this->OP_OTHERS,
                $this->OP_SUB_TOTAL,
                $this->PROFESSIONAL_FEE_SUB_TOTAL,
                $this->CHARGE_TOTAL,
                $this->VAT_TOTAL,
                $this->SP_TOTAL,
                $this->GOV_TOTAL,
                $this->P1_TOTAL,
                $this->P2_TOTAL,
                $this->OP_TOTAL,
                $this->PREPARED_BY_ID,
                $this->DATE_SIGNED,
                $this->OTHER_NAME
            );

            return Redirect::route('transactionsphic_edit', ['id' => $this->ID])->with('message', 'Successfully created');

        } else {

            $this->philHealthServices->Update(
                $this->ID,
                $this->CODE,
                $this->DATE,
                $this->LOCATION_ID,
                $this->CONTACT_ID,
                $this->DATE_ADMITTED,
                $this->TIME_ADMITTED,
                $this->DATE_DISCHARGED,
                $this->TIME_ADMITTED,
                $this->FINAL_DIAGNOSIS,
                $this->OTHER_DIAGNOSIS,
                $this->FIRST_CASE_RATE,
                $this->SECOND_CASE_RATE,
                $this->CHARGES_ROOM_N_BOARD,
                $this->CHARGES_DRUG_N_MEDICINE,
                $this->CHARGES_LAB_N_DIAGNOSTICS,
                $this->CHARGES_OPERATING_ROOM_FEE,
                $this->CHARGES_SUPPLIES,
                $this->CHARGES_OTHERS,
                $this->CHARGES_SUB_TOTAL,
                $this->OTHER_SPECIFY,
                $this->VAT_ROOM_N_BOARD,
                $this->VAT_DRUG_N_MEDICINE,
                $this->VAT_LAB_N_DIAGNOSTICS,
                $this->VAT_OPERATING_ROOM_FEE,
                $this->VAT_SUPPLIES,
                $this->VAT_OTHERS,
                $this->VAT_SUB_TOTAL,
                $this->SP_ROOM_N_BOARD,
                $this->SP_DRUG_N_MEDICINE,
                $this->SP_LAB_N_DIAGNOSTICS,
                $this->SP_OPERATING_ROOM_FEE,
                $this->SP_SUPPLIES,
                $this->SP_OTHERS,
                $this->SP_SUB_TOTAL,
                $this->GOV_ROOM_N_BOARD,
                $this->GOV_DRUG_N_MEDICINE,
                $this->GOV_LAB_N_DIAGNOSTICS,
                $this->GOV_OPERATING_ROOM_FEE,
                $this->GOV_SUPPLIES,
                $this->GOV_OTHERS,
                $this->GOV_SUB_TOTAL,
                $this->GOV_PCSO,
                $this->GOV_DSWD,
                $this->GOV_DOH,
                $this->GOV_HMO,
                $this->GOV_LINGAP,
                $this->P1_ROOM_N_BOARD,
                $this->P1_DRUG_N_MEDICINE,
                $this->P1_LAB_N_DIAGNOSTICS,
                $this->P1_OPERATING_ROOM_FEE,
                $this->P1_SUPPLIES,
                $this->P1_OTHERS,
                $this->P1_SUB_TOTAL,
                $this->P2_ROOM_N_BOARD,
                $this->P2_DRUG_N_MEDICINE,
                $this->P2_LAB_N_DIAGNOSTICS,
                $this->P2_OPERATING_ROOM_FEE,
                $this->P2_SUPPLIES,
                $this->P2_OTHERS,
                $this->P2_SUB_TOTAL,
                $this->OP_ROOM_N_BOARD,
                $this->OP_DRUG_N_MEDICINE,
                $this->OP_LAB_N_DIAGNOSTICS,
                $this->OP_OPERATING_ROOM_FEE,
                $this->OP_SUPPLIES,
                $this->OP_OTHERS,
                $this->OP_SUB_TOTAL,
                $this->PROFESSIONAL_FEE_SUB_TOTAL,
                $this->CHARGE_TOTAL,
                $this->VAT_TOTAL,
                $this->SP_TOTAL,
                $this->GOV_TOTAL,
                $this->P1_TOTAL,
                $this->P2_TOTAL,
                $this->OP_TOTAL,
                $this->PREPARED_BY_ID,
                $this->DATE_SIGNED,
                $this->OTHER_NAME
            );

            $this->Modify = false;
            session()->flash('message', 'Successfully updated');
        }




    }


    public function render()
    {
        return view('livewire.phil-health.phil-health-form');
    }
}
