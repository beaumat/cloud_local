<?php

namespace App\Services;

use App\Models\PhilHealth;
use App\Models\Hemodialysis;
use App\Models\PatientDoctor;
use App\Models\PhilhealthPayment;
use App\Models\PhilHealthProfFee;
use Illuminate\Support\Facades\DB;
use App\Models\PhilhealthDrugsMedicines;

class PhilHealthServices
{
    public string $DEFAULT_DIAGNOSIS = "CHRONIC KIDNEY DISEASE STAGE 5 : ";
    private float $DISCOUNT_PERCENT = 20;
    public float $P1_PHIC_AMOUNT = 2250;
    public float $DRUG_N_MEDINE_AMOUNT = 1100.00;
    public float $OPERATING_ROOM_FEE_AMOUNT = 370;
    public float $ROOM_FEE = 700;
    public float $SUPPLIES = 780;
    public float $PROF_FEE_AMOUNT = 450;
    public float $PROF_FEE_FIRST_CASE = 350;

    private $object;
    private $dateServices;
    private $systemSettingServices;

    public function __construct(
        ObjectServices $objectService,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices
    ) {
        $this->object = $objectService;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function get($ID)
    {
        return PhilHealth::where('ID', $ID)->first();
    }
    public function AutoMakeProfFeeDetails(int $PHIC_ID, int $PATIENT_ID, int $COUNT)
    {
        $TOTAL_FEE = 0;
        $TOTAL_DISC = 0;
        $TOTAL_FIRST_CASE = 0;
        $data = PatientDoctor::query()->select(['DOCTOR_ID'])->where("PATIENT_ID", $PATIENT_ID)->get();
        if ($data) {
            foreach ($data as $list) {
                $isDataExists = PhilHealthProfFee::where('PHIC_ID', $PHIC_ID)->where('CONTACT_ID', $list->DOCTOR_ID)->first();
                $AMOUNT = (float) $this->PROF_FEE_AMOUNT * $COUNT;
                $FIRST_CASE = (float) $this->PROF_FEE_FIRST_CASE * $COUNT;
                $DISCOUNT = $AMOUNT * ($this->DISCOUNT_PERCENT / 100);
                if (!$isDataExists) {
                    $this->StoreProfFee($PHIC_ID, $list->DOCTOR_ID, $AMOUNT, $DISCOUNT, $FIRST_CASE);
                } else {
                    $this->UpdateProfFee($isDataExists->ID, $AMOUNT, $DISCOUNT, $FIRST_CASE);
                }
                $TOTAL_FEE = $TOTAL_FEE + $AMOUNT;
                $TOTAL_DISC = $TOTAL_DISC + $DISCOUNT;
                $TOTAL_FIRST_CASE = $TOTAL_FIRST_CASE +  $FIRST_CASE;
            }
        }

        return [
            'TOTAL_FEE' => $TOTAL_FEE,
            'TOTAL_DISCOUNT' => $TOTAL_DISC,
            'TOTAL_FIRST_CASE' => $TOTAL_FIRST_CASE
        ];
    }
    public function getNumberOfTreatment(int $CONTACT_ID, int $LOCATION_ID, string $DATE_ADMITTED, string $DATE_DISCHARGED): int
    {
        $hemoCount = Hemodialysis::query()
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID');
                $join->on('s.DATE', '=', 'hemodialysis.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', 2)
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '2')
            ->whereBetween('hemodialysis.DATE', [$DATE_ADMITTED, $DATE_DISCHARGED])
            ->count();

        return $hemoCount;
    }
    public function DefaultEntry(int $ID)
    {
        $data = $this->get($ID);

        if ($data) {

            $NO_OF_TREATMENT = $this->getNumberOfTreatment($data->CONTACT_ID, $data->LOCATION_ID, $data->DATE_ADMITTED, $data->DATE_DISCHARGED);

            $DRUG_MED = (float) $this->DRUG_N_MEDINE_AMOUNT * $NO_OF_TREATMENT;
            $OPERATE_FEE = (float) ($this->OPERATING_ROOM_FEE_AMOUNT * $NO_OF_TREATMENT) + ($this->ROOM_FEE * $NO_OF_TREATMENT);
            $CHARGES_SUPPLIES = (float) $this->SUPPLIES * $NO_OF_TREATMENT;
            $C_SUB_TOTAL = (float) $DRUG_MED + $OPERATE_FEE + $CHARGES_SUPPLIES;

            $SP_SUB_TOTAL = (float)  $C_SUB_TOTAL *  ($this->DISCOUNT_PERCENT / 100);
            $AD_SUB_TOTAL = $C_SUB_TOTAL  -  $SP_SUB_TOTAL;
            $P1_SUB_TOTAL = (float) $this->P1_PHIC_AMOUNT * $NO_OF_TREATMENT;
            $OP_SUB_TOTAL = (float) $AD_SUB_TOTAL - $P1_SUB_TOTAL;

            $profArray = $this->AutoMakeProfFeeDetails($data->ID, $data->CONTACT_ID, $NO_OF_TREATMENT);
            $PROFESSIONAL_FEE_SUB_TOTAL  = (float) $profArray['TOTAL_FEE'];
            $PROFESSIONAL_DISCOUNT_SUB_TOTAL = (float) $profArray['TOTAL_DISCOUNT'];
            $PROFESSIONAL_P1_SUB_TOTAL = (float) $profArray['TOTAL_FIRST_CASE'];

            $CHARGE_TOTAL = $PROFESSIONAL_FEE_SUB_TOTAL + $C_SUB_TOTAL;
            $SP_TOTAL = $CHARGE_TOTAL * ($this->DISCOUNT_PERCENT / 100);
            $AD_TOTAL = $CHARGE_TOTAL -  $SP_TOTAL;
            $P1_TOTAL = $PROFESSIONAL_P1_SUB_TOTAL + $P1_SUB_TOTAL;
            $OP_TOTAL = $AD_TOTAL - $P1_TOTAL;

            PhilHealth::where('ID', $data->ID)
                ->update([
                    'CHARGES_DRUG_N_MEDICINE'           => $DRUG_MED,
                    'CHARGES_OPERATING_ROOM_FEE'        => $OPERATE_FEE,
                    'CHARGES_SUPPLIES'                  => $CHARGES_SUPPLIES,
                    'CHARGES_SUB_TOTAL'                 => $C_SUB_TOTAL,
                    'SP_SUB_TOTAL'                      => $SP_SUB_TOTAL,
                    'P1_SUB_TOTAL'                      => $P1_SUB_TOTAL,
                    'OP_SUB_TOTAL'                      => $OP_SUB_TOTAL,
                    'AD_SUB_TOTAL'                      => $AD_SUB_TOTAL,
                    'PROFESSIONAL_FEE_SUB_TOTAL'        => $PROFESSIONAL_FEE_SUB_TOTAL,
                    'PROFESSIONAL_DISCOUNT_SUB_TOTAL'   => $PROFESSIONAL_DISCOUNT_SUB_TOTAL,
                    'PROFESSIONAL_P1_SUB_TOTAL'         => $PROFESSIONAL_P1_SUB_TOTAL,
                    'CHARGE_TOTAL'                      => $CHARGE_TOTAL,
                    'SP_TOTAL'                          => $SP_TOTAL,
                    'P1_TOTAL'                          => $P1_TOTAL,
                    'AD_TOTAL'                          => $AD_TOTAL,
                    'OP_TOTAL'                          => $OP_TOTAL
                ]);
        }

        //got professional fee
    }

    public function preUpdate(
        int $ID,
        string $CODE,
        string $DATE,
        int $LOCATION_ID,
        int $CONTACT_ID,
        string $DATE_ADMITTED,
        string $TIME_ADMITTED,
        string $DATE_DISCHARGED,
        string $TIME_DISCHARGED,
        string $FINAL_DIAGNOSIS,
        string $OTHER_DIAGNOSIS,
        string $FIRST_CASE_RATE,
        string $SECOND_CASE_RATE
    ) {

        PhilHealth::where('ID', $ID)
            ->update([
                'CODE'              => $CODE,
                'DATE'              => $DATE,
                'LOCATION_ID'       => $LOCATION_ID,
                'CONTACT_ID'        => $CONTACT_ID,
                'DATE_ADMITTED'     => $DATE_ADMITTED,
                'TIME_ADMITTED'     => $TIME_ADMITTED,
                'DATE_DISCHARGED'   => $DATE_DISCHARGED,
                'TIME_DISCHARGED'   => $TIME_DISCHARGED,
                'FINAL_DIAGNOSIS'   => $FINAL_DIAGNOSIS,
                'OTHER_DIAGNOSIS'   => $OTHER_DIAGNOSIS,
                'FIRST_CASE_RATE'   => $FIRST_CASE_RATE,
                'SECOND_CASE_RATE'  => $SECOND_CASE_RATE
            ]);
    }
    public function preSave(
        string $CODE,
        string $DATE,
        int $LOCATION_ID,
        int $CONTACT_ID,
        string $DATE_ADMITTED,
        string $TIME_ADMITTED,
        string $DATE_DISCHARGED,
        string $TIME_DISCHARGED,
        string $FINAL_DIAGNOSIS,
        string $OTHER_DIAGNOSIS,
        string $FIRST_CASE_RATE,
        string $SECOND_CASE_RATE
    ): int {
        $ID = $this->object->ObjectNextID('PHILHEALTH');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PHILHEALTH');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PhilHealth::create([
            'ID'                => $ID,
            'RECORDED_ON'       => $this->dateServices->Now(),
            'CODE'              => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'              => $DATE,
            'LOCATION_ID'       => $LOCATION_ID,
            'CONTACT_ID'        => $CONTACT_ID,
            'DATE_ADMITTED'     => $DATE_ADMITTED,
            'TIME_ADMITTED'     => $TIME_ADMITTED,
            'DATE_DISCHARGED'   => $DATE_DISCHARGED,
            'TIME_DISCHARGED'   => $TIME_DISCHARGED,
            'FINAL_DIAGNOSIS'   => $FINAL_DIAGNOSIS,
            'OTHER_DIAGNOSIS'   => $OTHER_DIAGNOSIS,
            'FIRST_CASE_RATE'   => $FIRST_CASE_RATE,
            'SECOND_CASE_RATE'  => $SECOND_CASE_RATE,
            'STATUS_ID'         => 1,
            'STATUS_DATETIME'   => $this->dateServices->Now(),
        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        float $CHARGES_ROOM_N_BOARD,
        float $CHARGES_DRUG_N_MEDICINE,
        float $CHARGES_LAB_N_DIAGNOSTICS,
        float $CHARGES_OPERATING_ROOM_FEE,
        float $CHARGES_SUPPLIES,
        float $CHARGES_OTHERS,
        float $CHARGES_SUB_TOTAL,
        string $OTHER_SPECIFY,
        float $VAT_ROOM_N_BOARD,
        float $VAT_DRUG_N_MEDICINE,
        float $VAT_LAB_N_DIAGNOSTICS,
        float $VAT_OPERATING_ROOM_FEE,
        float $VAT_SUPPLIES,
        float $VAT_OTHERS,
        float $VAT_SUB_TOTAL,
        float $SP_ROOM_N_BOARD,
        float $SP_DRUG_N_MEDICINE,
        float $SP_LAB_N_DIAGNOSTICS,
        float $SP_OPERATING_ROOM_FEE,
        float $SP_SUPPLIES,
        float $SP_OTHERS,
        float $SP_SUB_TOTAL,
        float $GOV_ROOM_N_BOARD,
        float $GOV_DRUG_N_MEDICINE,
        float $GOV_LAB_N_DIAGNOSTICS,
        float $GOV_OPERATING_ROOM_FEE,
        float $GOV_SUPPLIES,
        float $GOV_OTHERS,
        float $GOV_SUB_TOTAL,
        bool $GOV_PCSO,
        bool $GOV_DSWD,
        bool $GOV_DOH,
        bool $GOV_HMO,
        bool $GOV_LINGAP,
        float $P1_ROOM_N_BOARD,
        float $P1_DRUG_N_MEDICINE,
        float $P1_LAB_N_DIAGNOSTICS,
        float $P1_OPERATING_ROOM_FEE,
        float $P1_SUPPLIES,
        float $P1_OTHERS,
        float $P1_SUB_TOTAL,
        float $P2_ROOM_N_BOARD,
        float $P2_DRUG_N_MEDICINE,
        float $P2_LAB_N_DIAGNOSTICS,
        float $P2_OPERATING_ROOM_FEE,
        float $P2_SUPPLIES,
        float $P2_OTHERS,
        float $P2_SUB_TOTAL,
        float $OP_ROOM_N_BOARD,
        float $OP_DRUG_N_MEDICINE,
        float $OP_LAB_N_DIAGNOSTICS,
        float $OP_OPERATING_ROOM_FEE,
        float $OP_SUPPLIES,
        float $OP_OTHERS,
        float $OP_SUB_TOTAL,
        float $PROFESSIONAL_FEE_SUB_TOTAL,
        float $PROFESSIONAL_DISCOUNT_SUB_TOTAL,
        float $CHARGE_TOTAL,
        float $VAT_TOTAL,
        float $SP_TOTAL,
        float $GOV_TOTAL,
        float $P1_TOTAL,
        float $P2_TOTAL,
        float $OP_TOTAL,
        int $PREPARED_BY_ID,
        string $DATE_SIGNED,
        string $OTHER_NAME
    ) {

        PhilHealth::where('ID', $ID)
            ->update([
                'CHARGES_ROOM_N_BOARD'          => $CHARGES_ROOM_N_BOARD,
                'CHARGES_DRUG_N_MEDICINE'       => $CHARGES_DRUG_N_MEDICINE,
                'CHARGES_LAB_N_DIAGNOSTICS'     => $CHARGES_LAB_N_DIAGNOSTICS,
                'CHARGES_OPERATING_ROOM_FEE'    => $CHARGES_OPERATING_ROOM_FEE,
                'CHARGES_SUPPLIES'              => $CHARGES_SUPPLIES,
                'CHARGES_OTHERS'                => $CHARGES_OTHERS,
                'CHARGES_SUB_TOTAL'             => $CHARGES_SUB_TOTAL,
                'OTHER_SPECIFY'                 => $OTHER_SPECIFY,
                'VAT_ROOM_N_BOARD'              => $VAT_ROOM_N_BOARD,
                'VAT_DRUG_N_MEDICINE'           => $VAT_DRUG_N_MEDICINE,
                'VAT_LAB_N_DIAGNOSTICS'         => $VAT_LAB_N_DIAGNOSTICS,
                'VAT_OPERATING_ROOM_FEE'        => $VAT_OPERATING_ROOM_FEE,
                'VAT_SUPPLIES'                  => $VAT_SUPPLIES,
                'VAT_OTHERS'                    => $VAT_OTHERS,
                'VAT_SUB_TOTAL'                 => $VAT_SUB_TOTAL,
                'SP_ROOM_N_BOARD'               => $SP_ROOM_N_BOARD,
                'SP_DRUG_N_MEDICINE'            => $SP_DRUG_N_MEDICINE,
                'SP_LAB_N_DIAGNOSTICS'          => $SP_LAB_N_DIAGNOSTICS,
                'SP_OPERATING_ROOM_FEE'         => $SP_OPERATING_ROOM_FEE,
                'SP_SUPPLIES'                   => $SP_SUPPLIES,
                'SP_OTHERS'                     => $SP_OTHERS,
                'SP_SUB_TOTAL'                  => $SP_SUB_TOTAL,
                'GOV_ROOM_N_BOARD'              => $GOV_ROOM_N_BOARD,
                'GOV_DRUG_N_MEDICINE'           => $GOV_DRUG_N_MEDICINE,
                'GOV_LAB_N_DIAGNOSTICS'         => $GOV_LAB_N_DIAGNOSTICS,
                'GOV_OPERATING_ROOM_FEE'        => $GOV_OPERATING_ROOM_FEE,
                'GOV_SUPPLIES'                  => $GOV_SUPPLIES,
                'GOV_OTHERS'                    => $GOV_OTHERS,
                'GOV_SUB_TOTAL'                 => $GOV_SUB_TOTAL,
                'GOV_PCSO'                      => $GOV_PCSO,
                'GOV_DSWD'                      => $GOV_DSWD,
                'GOV_DOH'                       => $GOV_DOH,
                'GOV_HMO'                       => $GOV_HMO,
                'GOV_LINGAP'                    => $GOV_LINGAP,
                'P1_ROOM_N_BOARD'               => $P1_ROOM_N_BOARD,
                'P1_DRUG_N_MEDICINE'            => $P1_DRUG_N_MEDICINE,
                'P1_LAB_N_DIAGNOSTICS'          => $P1_LAB_N_DIAGNOSTICS,
                'P1_OPERATING_ROOM_FEE'         => $P1_OPERATING_ROOM_FEE,
                'P1_SUPPLIES'                   => $P1_SUPPLIES,
                'P1_OTHERS'                     => $P1_OTHERS,
                'P1_SUB_TOTAL'                  => $P1_SUB_TOTAL,
                'P2_ROOM_N_BOARD'               => $P2_ROOM_N_BOARD,
                'P2_DRUG_N_MEDICINE'            => $P2_DRUG_N_MEDICINE,
                'P2_LAB_N_DIAGNOSTICS'          => $P2_LAB_N_DIAGNOSTICS,
                'P2_OPERATING_ROOM_FEE'         => $P2_OPERATING_ROOM_FEE,
                'P2_SUPPLIES'                   => $P2_SUPPLIES,
                'P2_OTHERS'                     => $P2_OTHERS,
                'P2_SUB_TOTAL'                  => $P2_SUB_TOTAL,
                'OP_ROOM_N_BOARD'               => $OP_ROOM_N_BOARD,
                'OP_DRUG_N_MEDICINE'            => $OP_DRUG_N_MEDICINE,
                'OP_LAB_N_DIAGNOSTICS'          => $OP_LAB_N_DIAGNOSTICS,
                'OP_OPERATING_ROOM_FEE'         => $OP_OPERATING_ROOM_FEE,
                'OP_SUPPLIES'                   => $OP_SUPPLIES,
                'OP_OTHERS'                     => $OP_OTHERS,
                'OP_SUB_TOTAL'                  => $OP_SUB_TOTAL,
                'PROFESSIONAL_FEE_SUB_TOTAL'    => $PROFESSIONAL_FEE_SUB_TOTAL,
                'PROFESSIONAL_DISCOUNT_SUB_TOTAL' => $PROFESSIONAL_DISCOUNT_SUB_TOTAL,
                'CHARGE_TOTAL'                  => $CHARGE_TOTAL,
                'VAT_TOTAL'                     => $VAT_TOTAL,
                'SP_TOTAL'                      => $SP_TOTAL,
                'GOV_TOTAL'                     => $GOV_TOTAL,
                'P1_TOTAL'                      => $P1_TOTAL,
                'P2_TOTAL'                      => $P2_TOTAL,
                'OP_TOTAL'                      => $OP_TOTAL,
                'PREPARED_BY_ID'                => $PREPARED_BY_ID == 0 ? null : $PREPARED_BY_ID,
                'DATE_SIGNED'                   => $DATE_SIGNED == '' ? null : $DATE_SIGNED,
                'OTHER_NAME'                    => $OTHER_NAME ?? null,
            ]);
    }
    public function Delete(int $ID)
    {
        PhilhealthPayment::where('PHILHEALTH_ID', $ID)->delete();
        PhilhealthDrugsMedicines::where('PHILHEALTH_ID', $ID)->delete();
        PhilHealthProfFee::where('PHIC_ID', $ID)->delete();
        PhilHealth::where('ID', $ID)->delete();
    }
    public function Search($search, int $locationId, int $perPage)
    {
        return PhilHealth::query()
            ->select([
                'philhealth.ID',
                'philhealth.CODE',
                'philhealth.DATE',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.CHARGE_TOTAL',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL '),
                'philhealth.P1_TOTAL',
                'philhealth.PAYMENT_AMOUNT'
            ])
            ->join('contact as c', 'c.ID', '=', 'philhealth.CONTACT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'philhealth.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'philhealth.STATUS_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('philhealth.CODE', 'like', '%' . $search . '%')
                        ->orWhere('philhealth.CHARGE_TOTAL', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('philhealth.ID', 'desc')
            ->paginate($perPage);
    }

    public function PatientRecord($search, int $contact_id, int $perPage)
    {
        return PhilHealth::query()
            ->select([
                'philhealth.ID',
                'philhealth.CODE',
                'philhealth.DATE',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.CHARGE_TOTAL',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL '),
                'philhealth.P1_TOTAL',
                'philhealth.PAYMENT_AMOUNT'
            ])
            ->join('location as l', 'l.ID', '=', 'philhealth.LOCATION_ID')
            ->join('document_status_map as s', 's.ID', '=', 'philhealth.STATUS_ID')
            ->where('philhealth.CONTACT_ID', '=', $contact_id)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('philhealth.CODE', 'like', '%' . $search . '%')
                        ->orWhere('philhealth.CHARGE_TOTAL', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('philhealth.ID', 'desc')
            ->paginate($perPage);
    }

    public function getProfFee($ID)
    {
        return PhilHealthProfFee::query()
            ->select([
                'philhealth_prof_fee.ID',
                'philhealth_prof_fee.CONTACT_ID',
                'philhealth_prof_fee.AMOUNT',
                'philhealth_prof_fee.DISCOUNT',
                'philhealth_prof_fee.FIRST_CASE',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN'
            ])
            ->join('contact as c', 'c.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('PHIC_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();
    }
    private function getLine($Id): int
    {
        return (int) PhilHealthProfFee::where('PHIC_ID', $Id)->max('LINE_NO');
    }
    public function StoreProfFee(int $PHIC_ID, int $CONTACT_ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        $ID = $this->object->ObjectNextID('PHILHEALTH_PROF_FEE');
        $LINE_NO = $this->getLine($PHIC_ID) + 1;

        PhilHealthProfFee::create([
            'ID'         => $ID,
            'PHIC_ID'    => $PHIC_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'AMOUNT'     => $AMOUNT,
            'LINE_NO'    => $LINE_NO,
            'DISCOUNT'   => $DISCOUNT,
            'FIRST_CASE' => $FIRST_CASE


        ]);
    }
    public function UpdateProfFee(int $ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        PhilHealthProfFee::where('ID', $ID)
            ->update([
                'AMOUNT'        => $AMOUNT,
                'DISCOUNT'      => $DISCOUNT,
                'FIRST_CASE'    => $FIRST_CASE
            ]);
    }

    public function DeleteProfFee(int $ID)
    {
        PhilHealthProfFee::where('ID', $ID)->delete();
    }
    public function DrugMedicineStore(
        int $PHILHEALTH_ID,
        string $GENERIC_NAME,
        float $QUANTITY,
        string $DOSSAGE,
        string $ROUTE,
        string $FREQUENCY,
        float $TOTAL_COST,
        string $CONT_GENERIC_NAME,
        float $CONT_QUANTITY,
        string $CONT_DOSSAGE,
        string $CONT_ROUTE,
        string $CONT_FREQUENCY,
        float $CONT_TOTAL_COST
    ) {
        $ID = $this->object->ObjectNextID('PHILHEALTH_DRUGS_MEDICINES');

        PhilhealthDrugsMedicines::create([
            'ID' => $ID,
            'PHILHEALTH_ID'         => $PHILHEALTH_ID,
            'RECORDED_ON'           => $this->dateServices->Now(),
            'GENERIC_NAME'          => $GENERIC_NAME,
            'QUANTITY'              => $QUANTITY,
            'DOSSAGE'               => $DOSSAGE,
            'ROUTE'                 => $ROUTE,
            'FREQUENCY'             => $FREQUENCY,
            'TOTAL_COST'            => $TOTAL_COST,
            'CONT_GENERIC_NAME'     => $CONT_GENERIC_NAME,
            'CONT_QUANTITY'         => $CONT_QUANTITY,
            'CONT_DOSSAGE'          => $CONT_DOSSAGE,
            'CONT_ROUTE'            => $CONT_ROUTE,
            'CONT_FREQUENCY'        => $CONT_FREQUENCY,
            'CONT_TOTAL_COST'       => $CONT_TOTAL_COST

        ]);
    }
    public function DrugMedicineUpdate(
        int $ID,
        int $PHILHEALTH_ID,
        string $GENERIC_NAME,
        float $QUANTITY,
        string $DOSSAGE,
        string $ROUTE,
        string $FREQUENCY,
        float $TOTAL_COST,
        string $CONT_GENERIC_NAME,
        float $CONT_QUANTITY,
        string $CONT_DOSSAGE,
        string $CONT_ROUTE,
        string $CONT_FREQUENCY,
        float $CONT_TOTAL_COST
    ) {
        PhilhealthDrugsMedicines::where('ID', $ID)->update([
            'PHILHEALTH_ID'         => $PHILHEALTH_ID,
            'GENERIC_NAME'          => $GENERIC_NAME,
            'QUANTITY'              => $QUANTITY,
            'DOSSAGE'               => $DOSSAGE,
            'ROUTE'                 => $ROUTE,
            'FREQUENCY'             => $FREQUENCY,
            'TOTAL_COST'            => $TOTAL_COST,
            'CONT_GENERIC_NAME'     => $CONT_GENERIC_NAME,
            'CONT_QUANTITY'         => $CONT_QUANTITY,
            'CONT_DOSSAGE'          => $CONT_DOSSAGE,
            'CONT_ROUTE'            => $CONT_ROUTE,
            'CONT_FREQUENCY'        => $CONT_FREQUENCY,
            'CONT_TOTAL_COST'       => $CONT_TOTAL_COST

        ]);
    }
    public function DrugMedicineDelete(int $ID)
    {
        PhilhealthDrugsMedicines::where('ID', $ID,)->delete();
    }

    public function DrugMedicineList(int $PHILHEALTH_ID): object
    {
        return PhilhealthDrugsMedicines::query()
            ->select([
                'ID',
                'GENERIC_NAME',
                'QUANTITY',
                'DOSSAGE',
                'ROUTE',
                'FREQUENCY',
                'TOTAL_COST',
                'CONT_GENERIC_NAME',
                'CONT_QUANTITY',
                'CONT_DOSSAGE',
                'CONT_ROUTE',
                'CONT_FREQUENCY',
                'CONT_TOTAL_COST'
            ])
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->orderBy('ID', 'asc')
            ->get();
    }
    public function GetDrugMedicine(int $ID): object
    {
        return PhilhealthDrugsMedicines::where('ID', $ID)->first();
    }
    public function PaymentGet(int $ID)
    {
        return PhilhealthPayment::where('ID', $ID)->first();
    }
    public function PaymentStore(int $PHILHEALTH_ID, string $RECEIVED_DATE, float $AMOUNT, string $REF_NO)
    {
        $ID = $this->object->ObjectNextID('PHILHEALTH_PAYMENT');

        PhilhealthPayment::create([
            'ID'            => $ID,
            'PHILHEALTH_ID' => $PHILHEALTH_ID,
            'RECORDED_ON'   => $this->dateServices->Now(),
            'RECEIVED_DATE' => $RECEIVED_DATE,
            'AMOUNT'        => $AMOUNT,
            'REF_NO'        => $REF_NO
        ]);
    }
    public function PaymentUpdate(int $ID, int $PHILHEALTH_ID, string $RECEIVED_DATE, float $AMOUNT, string $REF_NO)
    {

        PhilhealthPayment::where('ID', $ID)
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->update([
                'RECEIVED_DATE' => $RECEIVED_DATE,
                'AMOUNT'        => $AMOUNT,
                'REF_NO'        => $REF_NO
            ]);
    }
    public function PaymentDelete(int $ID, int $PHILHEALTH_ID)
    {
        PhilhealthPayment::where('ID', $ID)
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->delete();
    }
    public function PaymentList(int $PHILHEALTH_ID)
    {
        $result =  PhilhealthPayment::query()
            ->select([
                'ID',
                'RECORDED_ON',
                'RECEIVED_DATE',
                'AMOUNT',
                'REF_NO'
            ])
            ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
            ->get();

        return $result;
    }
    public function UpdatePayment(int $PHILHEALTH_ID)
    {
        $data = $this->get($PHILHEALTH_ID);
        if ($data) {

            $pay =  PhilhealthPayment::query()
                ->select([
                    DB::raw('SUM(AMOUNT) as TOTAL'),
                ])
                ->where('PHILHEALTH_ID', $PHILHEALTH_ID)
                ->first();

            if ((float) $pay->TOTAL >= (float) $data->P1_TOTAL) {
                $STATUS_ID = 11; // Paid
            } else {
                $STATUS_ID = 1; //PENDING
            }
            PhilHealth::where('ID', $PHILHEALTH_ID)
                ->update([
                    'PAYMENT_AMOUNT' =>  $pay->TOTAL,
                    'STATUS_ID' => $STATUS_ID
                ]);
        }
    }
}
