<?php

namespace App\Services;

use App\Models\Bill;
use App\Models\PhilHealth;
use App\Models\Hemodialysis;
use App\Models\PatientDoctor;
use App\Models\PhilHealthProfFee;
use Illuminate\Support\Facades\DB;
use App\Models\PhilhealthDrugsMedicines;
use App\Models\PhilhealthItemAdjustment;

class PhilHealthServices
{
    public int $TAX_ID = 12;
    public int $TERM_ID = 2;
    public int $PROFESSIONAL_FEE_ACCOUNT_ID = 243;
    public float $TAX = 0.02;
    public string $FIRST_CASE_RATE = "90935";
    public string $DEFAULT_DIAGNOSIS = "CHRONIC KIDNEY DISEASE STAGE 5 TO ";
    public string $DEFAULT_DIAGNOSIS2 = "CKD Stage 5 Sec to ";
    public string $CHIEF_OF_COMPLAINT_DEFAULT = 'HEMODIALYSIS';
    public string $ADMITTING_DIAGNOSIS_DEFAULT = 'CHRONIC KIDNEY DISEASE';
    public string $FINAL_DIAGNOSIS_DEFAULT;
    public string $HISTORY_OF_PRESENT_ILLNESS_DEFAULT = 'CHRONIC KIDNEY DISEASE STAGE 5';


    public float $OP_ROOM_N_BOARD = 0;
    public float $OP_DRUG_N_MEDICINE = 0;
    public float $OP_LAB_N_DIAGNOSTICS = 0;
    public float $OP_OPERATING_ROOM_FEE = 0;
    public float $OP_SUPPLIES = 0;
    public float $OP_OTHERS = 0;
    public float $OP_SUB_TOTAL = 0;
    private float $DISCOUNT_PERCENT = 20;
    public float $LAB_N_DIAGNOSTICS_AMOUNT = 0;
    public float $P1_PHIC_AMOUNT = 0;
    public float $DRUG_N_MEDINE_AMOUNT = 0;
    public float $OPERATING_ROOM_FEE_AMOUNT = 0;
    public float $OTHER_CHARGES_AMOUNT = 0;
    public float $ROOM_FEE = 0;
    public float $SUPPLIES = 0; // 1082;
    public float $PROF_FEE_AMOUNT = 0; //437.50;
    public float $PROF_FEE_HIDE = 0;
    public int $PHIL_HEALTH_ITEM_ID = 2;

    private $object;
    private $dateServices;
    private $systemSettingServices;
    private $philHealthSoaCustomServices;
    private $locationServices;
    private $serviceChargeServices;
    private $billingServices;
    private $paymentTermServices;
    private $accountJournalServices;
    private $invoiceServices;
    private $itemServices;
    private $priceLevelLineServices;
    private $taxServices;
    private $computeServices;
    public function __construct(
        ObjectServices $objectService,
        DateServices $dateServices,
        SystemSettingServices $systemSettingServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices,
        LocationServices $locationServices,
        ServiceChargeServices $serviceChargeServices,
        BillingServices $billingServices,
        PaymentTermServices $paymentTermServices,
        AccountJournalServices $accountJournalServices,
        InvoiceServices $invoiceServices,
        ItemServices $itemServices,
        PriceLevelLineServices $priceLevelLineServices,
        TaxServices $taxServices,
        ComputeServices $computeServices
    ) {
        $this->object = $objectService;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
        $this->locationServices = $locationServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->billingServices = $billingServices;
        $this->paymentTermServices = $paymentTermServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->invoiceServices = $invoiceServices;
        $this->itemServices = $itemServices;
        $this->priceLevelLineServices = $priceLevelLineServices;
        $this->taxServices = $taxServices;
        $this->computeServices = $computeServices;
    }
    public function get($ID)
    {
        return PhilHealth::where('ID', $ID)->first();
    }

    public function getCF4(int $ID)
    {
        return PhilHealth::select([
            'RR_NO',
            'CF4_AD_NOTES',
            'CF4_DD_NOTES',
            'CF4_COMPLAINT',
            'CF4_HPI',
            'CF4_PPMH',
            'CONTACT_ID'
        ])->where('ID', $ID)
            ->first();
    }
    public function getPrint($ID)
    {
        $result = PhilHealth::query()
            ->select([
                'philhealth.ID',
                'philhealth.CODE',
                'philhealth.DATE',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'c.FINAL_DIAGNOSIS',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as CONTACT_NAME"),
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL'),
                'philhealth.P1_TOTAL'
            ])
            ->join('contact as c', 'c.ID', '=', 'philhealth.CONTACT_ID')
            ->where('philhealth.ID', '=', $ID)
            ->first();

        return $result;
    }
    public function setCF4Update(int $ID, string $RR_NO, string $CF4_AD_NOTES = '', string $CF4_DD_NOTES = '', string $CF4_COMPLAINT = '', string $CF4_HPI = '', string $CF4_PPMH = '')
    {
        PhilHealth::where('ID', '=', $ID)
            ->update([
                'RR_NO' => $RR_NO,
                'CF4_AD_NOTES' => $CF4_AD_NOTES == '' ? null : $CF4_AD_NOTES,
                'CF4_DD_NOTES' => $CF4_DD_NOTES == '' ? null : $CF4_DD_NOTES,
                'CF4_COMPLAINT' => $CF4_COMPLAINT == '' ? null : $CF4_COMPLAINT,
                'CF4_HPI' => $CF4_HPI == '' ? null : $CF4_HPI,
                'CF4_PPMH' => $CF4_PPMH == '' ? null : $CF4_PPMH
            ]);
    }
    public function AutoMakeProfFeeDetails(int $PHIC_ID, int $PATIENT_ID, int $COUNT)
    {
        $TOTAL_FEE = 0;
        $TOTAL_DISC = 0;
        $TOTAL_FIRST_CASE = 0;

        $data = PatientDoctor::query()->select(['DOCTOR_ID'])
            ->where("PATIENT_ID", $PATIENT_ID)
            ->get();

        if ($data) {
            foreach ($data as $list) {
                $isDataExists = PhilHealthProfFee::where('PHIC_ID', $PHIC_ID)
                    ->where('CONTACT_ID', '=', $list->DOCTOR_ID)
                    ->first();

                $AMOUNT = (float) $this->PROF_FEE_AMOUNT * $COUNT;
                $LESS_AMT = (float) $this->PROF_FEE_AMOUNT * ($this->DISCOUNT_PERCENT / 100);
                $NEW_LESS = (float) $this->PROF_FEE_AMOUNT - $LESS_AMT;
                $FIRST_CASE = (float) $NEW_LESS * $COUNT; // FIXED


                if ($this->PROF_FEE_HIDE > 0) {
                    $AMOUNT = (float) $this->PROF_FEE_HIDE * $COUNT;
                    $LESS_AMT_HIDE = (float) $this->PROF_FEE_HIDE * ($this->DISCOUNT_PERCENT / 100);
                    $NEW_LESS = (float) $this->PROF_FEE_HIDE - $LESS_AMT_HIDE;
                }

                $DISCOUNT = $AMOUNT * ($this->DISCOUNT_PERCENT / 100);

                if (!$isDataExists) {

                    // clean
                    $this->StoreProfFee(
                        $PHIC_ID,
                        $list->DOCTOR_ID,
                        $AMOUNT,
                        $DISCOUNT,
                        $FIRST_CASE
                    );
                } else {
                    $this->UpdateProfFee(
                        $isDataExists->ID,
                        $AMOUNT,
                        $DISCOUNT,
                        $FIRST_CASE
                    );
                }

                $TOTAL_FEE = $TOTAL_FEE + $AMOUNT;
                $TOTAL_DISC = $TOTAL_DISC + $DISCOUNT;
                $TOTAL_FIRST_CASE = $TOTAL_FIRST_CASE + $FIRST_CASE;
            }
        }
        $dataReturn = [
            'TOTAL_FEE' => $TOTAL_FEE,
            'TOTAL_DISCOUNT' => $TOTAL_DISC,
            'TOTAL_FIRST_CASE' => $TOTAL_FIRST_CASE
        ];


        return $dataReturn;
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
            ->where('s.USE_PHIC', '=', 0)
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '2')
            ->whereBetween('hemodialysis.DATE', [$DATE_ADMITTED, $DATE_DISCHARGED])
            ->count();

        return $hemoCount;
    }
    private function scCheck(int $req_ITEM_ID, array $scItem = [])
    {

        foreach ($scItem as $i) {
            if ($req_ITEM_ID == $i['ITEM_ID']) {
                return true;
            }
        }

        return false;
    }
    private function CustomSoa(int $PATIENT_ID, int $LOCATON_ID, string $DATE_DISCHARGE)
    {
        $loc = $this->locationServices->get($LOCATON_ID);

        if ($loc->PHIC_FORM_MODIFY == true) {

            $scItem = $this->serviceChargeServices->GetItemForCustomSoa($DATE_DISCHARGE, $PATIENT_ID, $LOCATON_ID);
            $customSOA = $this->philHealthSoaCustomServices->CollectionRequirements($LOCATON_ID, $scItem);
            foreach ($customSOA as $st) {
                $req = $this->philHealthSoaCustomServices->GetList(SOA_CUSTOM_ID: $st['ID']);
                $con = 0;
                $got_con = 0;
                foreach ($req as $r) {
                    if ($this->scCheck($r->ITEM_ID, $scItem)) {
                        $got_con++;
                    }
                    $con++;
                }

                if ($con == $got_con) {
                    $soaData = $this->philHealthSoaCustomServices->Get($st['ID'], $LOCATON_ID);
                    if ($soaData) {

                        $this->LAB_N_DIAGNOSTICS_AMOUNT = $soaData->LAB_DIAG ?? 0;
                        $this->DRUG_N_MEDINE_AMOUNT = $soaData->DRUG_MED ?? 0;
                        $this->OPERATING_ROOM_FEE_AMOUNT = $soaData->OPERATING_ROOM_FEE ?? 0;
                        $this->OTHER_CHARGES_AMOUNT = $soaData->ADMIN_OTHER_FEE ?? 0;
                        $this->ROOM_FEE = $soaData->OPERATING_ROOM_FEE;
                        $this->SUPPLIES = $soaData->SUPPLIES ?? 0;

                        $this->OP_ROOM_N_BOARD = 0;
                        $this->OP_DRUG_N_MEDICINE = $soaData->DRUG_MED_PK ?? 0;
                        $this->OP_LAB_N_DIAGNOSTICS = $soaData->LAB_DIAG_PK ?? 0;
                        $this->OP_OPERATING_ROOM_FEE = $soaData->OPERATING_ROOM_FEE_PK ?? 0;
                        $this->OP_SUPPLIES = $soaData->SUPPLIES_PK ?? 0;
                        $this->OP_OTHERS = $soaData->ADMIN_OTHER_FEE_PK ?? 0;

                        $this->PROF_FEE_AMOUNT = $soaData->ACTUAL_FEE ?? 0;
                        $this->PROF_FEE_HIDE = $soaData->HIDE_FEE ?? 0;

                        $this->OP_SUB_TOTAL = $this->OP_DRUG_N_MEDICINE + $this->OP_LAB_N_DIAGNOSTICS + $this->OP_OPERATING_ROOM_FEE + $this->OP_SUPPLIES + $this->OP_OTHERS;
                    }
                    return;
                }
            }
        }
        // By Default
        $soaData = $this->philHealthSoaCustomServices->GetFirst($LOCATON_ID);
        if ($soaData) {

            $this->LAB_N_DIAGNOSTICS_AMOUNT = $soaData->LAB_DIAG ?? 0;
            $this->DRUG_N_MEDINE_AMOUNT = $soaData->DRUG_MED ?? 0;
            $this->OPERATING_ROOM_FEE_AMOUNT = $soaData->OPERATING_ROOM_FEE ?? 0;
            $this->OTHER_CHARGES_AMOUNT = $soaData->ADMIN_OTHER_FEE ?? 0;
            $this->ROOM_FEE = $soaData->OPERATING_ROOM_FEE;
            $this->SUPPLIES = $soaData->SUPPLIES ?? 0;

            $this->OP_ROOM_N_BOARD = 0;
            $this->OP_DRUG_N_MEDICINE = $soaData->DRUG_MED_PK ?? 0;
            $this->OP_LAB_N_DIAGNOSTICS = $soaData->LAB_DIAG_PK ?? 0;
            $this->OP_OPERATING_ROOM_FEE = $soaData->OPERATING_ROOM_FEE_PK ?? 0;
            $this->OP_SUPPLIES = $soaData->SUPPLIES_PK ?? 0;
            $this->OP_OTHERS = $soaData->ADMIN_OTHER_FEE_PK ?? 0;

            $this->PROF_FEE_AMOUNT = $soaData->ACTUAL_FEE ?? 0;
            $this->PROF_FEE_HIDE = $soaData->HIDE_FEE ?? 0;


            $this->OP_SUB_TOTAL = $this->OP_DRUG_N_MEDICINE + $this->OP_LAB_N_DIAGNOSTICS + $this->OP_OPERATING_ROOM_FEE + $this->OP_SUPPLIES + $this->OP_OTHERS;
        }
    }
    public function DefaultEntry(int $ID)
    {

        // initialize formula.

        $data = $this->get($ID);

        if ($data) {
            //Get Default Custom Variable
            $this->CustomSoa(
                $data->CONTACT_ID,
                $data->LOCATION_ID,
                $data->DATE_DISCHARGED
            );

            $NO_OF_TREATMENT = $this->getNumberOfTreatment(
                $data->CONTACT_ID,
                $data->LOCATION_ID,
                $data->DATE_ADMITTED,
                $data->DATE_DISCHARGED
            );

            $LAB_N_DIAGNOS = $this->LAB_N_DIAGNOSTICS_AMOUNT * $NO_OF_TREATMENT;
            $DRUG_MED = (float) $this->DRUG_N_MEDINE_AMOUNT * $NO_OF_TREATMENT;
            $OPERATE_FEE = (float) $this->OPERATING_ROOM_FEE_AMOUNT * $NO_OF_TREATMENT; //
            $CHARGES_SUPPLIES = (float) $this->SUPPLIES * $NO_OF_TREATMENT;
            $CHARGES_OTHERS = (float) $this->OTHER_CHARGES_AMOUNT * $NO_OF_TREATMENT;
            $C_SUB_TOTAL = (float) $DRUG_MED + $OPERATE_FEE + $CHARGES_SUPPLIES + $LAB_N_DIAGNOS + $CHARGES_OTHERS;

            $SP_SUB_TOTAL = (float) $C_SUB_TOTAL * ($this->DISCOUNT_PERCENT / 100);
            $AD_SUB_TOTAL = $C_SUB_TOTAL - $SP_SUB_TOTAL;
            $P1_SUB_TOTAL = $AD_SUB_TOTAL - $this->OP_SUB_TOTAL;
            $OP_SUB_TOTAL = $this->OP_SUB_TOTAL;

            $profArray = $this->AutoMakeProfFeeDetails($data->ID, $data->CONTACT_ID, $NO_OF_TREATMENT);

            $PROFESSIONAL_FEE_SUB_TOTAL = (float) $profArray['TOTAL_FEE'];
            $PROFESSIONAL_DISCOUNT_SUB_TOTAL = (float) $profArray['TOTAL_DISCOUNT'];
            $PROFESSIONAL_P1_SUB_TOTAL = (float) $profArray['TOTAL_FIRST_CASE'];

            $CHARGE_TOTAL = $PROFESSIONAL_FEE_SUB_TOTAL + $C_SUB_TOTAL;
            $SP_TOTAL = $CHARGE_TOTAL * ($this->DISCOUNT_PERCENT / 100);
            if ($this->PROF_FEE_HIDE == 0) {
                $AD_TOTAL = $CHARGE_TOTAL - $SP_TOTAL;
                $P1_TOTAL = $PROFESSIONAL_P1_SUB_TOTAL + $P1_SUB_TOTAL;
                $OP_TOTAL = $this->OP_SUB_TOTAL;
            } else {
                $AD_TOTAL = $PROFESSIONAL_P1_SUB_TOTAL + $P1_SUB_TOTAL;
                $P1_TOTAL = $PROFESSIONAL_P1_SUB_TOTAL + $P1_SUB_TOTAL;
                $OP_TOTAL = 0;
            }


            PhilHealth::where('ID', $data->ID)
                ->update([
                    'CHARGES_DRUG_N_MEDICINE' => $DRUG_MED,
                    'CHARGES_LAB_N_DIAGNOSTICS' => $LAB_N_DIAGNOS,
                    'CHARGES_OPERATING_ROOM_FEE' => $OPERATE_FEE,
                    'CHARGES_SUPPLIES' => $CHARGES_SUPPLIES,
                    'CHARGES_SUB_TOTAL' => $C_SUB_TOTAL,
                    'CHARGES_OTHERS' => $CHARGES_OTHERS,
                    'SP_SUB_TOTAL' => $SP_SUB_TOTAL,
                    'P1_SUB_TOTAL' => $P1_SUB_TOTAL,
                    'OP_SUB_TOTAL' => $OP_SUB_TOTAL,
                    'AD_SUB_TOTAL' => $AD_SUB_TOTAL,
                    'PROFESSIONAL_FEE_SUB_TOTAL' => $PROFESSIONAL_FEE_SUB_TOTAL,
                    'PROFESSIONAL_DISCOUNT_SUB_TOTAL' => $PROFESSIONAL_DISCOUNT_SUB_TOTAL,
                    'PROFESSIONAL_P1_SUB_TOTAL' => $PROFESSIONAL_P1_SUB_TOTAL,
                    'CHARGE_TOTAL' => $CHARGE_TOTAL,
                    'SP_TOTAL' => $SP_TOTAL,
                    'P1_TOTAL' => $P1_TOTAL,
                    'AD_TOTAL' => $AD_TOTAL,
                    'OP_TOTAL' => $OP_TOTAL,
                    'OP_ROOM_N_BOARD' => $this->OP_ROOM_N_BOARD,
                    'OP_DRUG_N_MEDICINE' => $this->OP_DRUG_N_MEDICINE,
                    'OP_LAB_N_DIAGNOSTICS' => $this->OP_LAB_N_DIAGNOSTICS,
                    'OP_OPERATING_ROOM_FEE' => $this->OP_OPERATING_ROOM_FEE,
                    'OP_SUPPLIES' => $this->OP_SUPPLIES,
                    'OP_OTHERS' => $this->OP_OTHERS
                ]);
        }

        //got professional fee
    }
    public function preUpdate(int $ID, string $CODE, string $DATE, int $LOCATION_ID, int $CONTACT_ID, string $DATE_ADMITTED, string $TIME_ADMITTED, string $DATE_DISCHARGED, string $TIME_DISCHARGED, string $FINAL_DIAGNOSIS, string $OTHER_DIAGNOSIS, string $FIRST_CASE_RATE, string $SECOND_CASE_RATE)
    {

        PhilHealth::where('ID', $ID)
            ->update([
                'CODE' => $CODE,
                'DATE' => $DATE,
                'LOCATION_ID' => $LOCATION_ID,
                'CONTACT_ID' => $CONTACT_ID,
                'DATE_ADMITTED' => $DATE_ADMITTED,
                'TIME_ADMITTED' => $TIME_ADMITTED,
                'DATE_DISCHARGED' => $DATE_DISCHARGED,
                'TIME_DISCHARGED' => $TIME_DISCHARGED,
                'FINAL_DIAGNOSIS' => $FINAL_DIAGNOSIS,
                'OTHER_DIAGNOSIS' => $OTHER_DIAGNOSIS,
                'FIRST_CASE_RATE' => $FIRST_CASE_RATE,
                'SECOND_CASE_RATE' => $SECOND_CASE_RATE,
                'TIME_HIDE' => date('H:i:s', strtotime($TIME_ADMITTED . ' +4 hours'))
            ]);
    }
    public function preSave(string $CODE, string $DATE, int $LOCATION_ID, int $CONTACT_ID, string $DATE_ADMITTED, string $TIME_ADMITTED, string $DATE_DISCHARGED, string $TIME_DISCHARGED, string $FINAL_DIAGNOSIS, string $OTHER_DIAGNOSIS, string $FIRST_CASE_RATE, string $SECOND_CASE_RATE): int
    {
        
        $ID = $this->object->ObjectNextID('PHILHEALTH');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PHILHEALTH');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        PhilHealth::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'LOCATION_ID' => $LOCATION_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'DATE_ADMITTED' => $DATE_ADMITTED,
            'TIME_ADMITTED' => $TIME_ADMITTED,
            'DATE_DISCHARGED' => $DATE_DISCHARGED,
            'TIME_DISCHARGED' => $TIME_DISCHARGED,
            'TIME_HIDE' => date('H:i:s', strtotime($TIME_ADMITTED . ' +4 hours')),
            'FINAL_DIAGNOSIS' => $FINAL_DIAGNOSIS,
            'OTHER_DIAGNOSIS' => $OTHER_DIAGNOSIS,
            'FIRST_CASE_RATE' => $FIRST_CASE_RATE,
            'SECOND_CASE_RATE' => $SECOND_CASE_RATE,
            'STATUS_ID' => 1,
            'STATUS_DATETIME' => $this->dateServices->Now(),
        ]);

        $this->setCF4Update($ID, 20);
        return $ID;
    }
    public function PreSaveTemp(int $CONTACT_ID, int $LOCATION_ID, )
    {

        $ID = $this->object->ObjectNextID('PHILHEALTH');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('PHILHEALTH');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));
        PhilHealth::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $this->dateServices->NowDate(),
            'LOCATION_ID' => $LOCATION_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'STATUS_ID' => 1,
            'STATUS_DATETIME' => $this->dateServices->Now(),
            'IS_TEMP' => 1
        ]);

        return $ID;
    }
    public function PrintEmpty(int $PATIENT_ID)
    {
    }
    public function Update(int $ID, float $CHARGES_ROOM_N_BOARD, float $CHARGES_DRUG_N_MEDICINE, float $CHARGES_LAB_N_DIAGNOSTICS, float $CHARGES_OPERATING_ROOM_FEE, float $CHARGES_SUPPLIES, float $CHARGES_OTHERS, float $CHARGES_SUB_TOTAL, string $OTHER_SPECIFY, float $VAT_ROOM_N_BOARD, float $VAT_DRUG_N_MEDICINE, float $VAT_LAB_N_DIAGNOSTICS, float $VAT_OPERATING_ROOM_FEE, float $VAT_SUPPLIES, float $VAT_OTHERS, float $VAT_SUB_TOTAL, float $SP_ROOM_N_BOARD, float $SP_DRUG_N_MEDICINE, float $SP_LAB_N_DIAGNOSTICS, float $SP_OPERATING_ROOM_FEE, float $SP_SUPPLIES, float $SP_OTHERS, float $SP_SUB_TOTAL, float $GOV_ROOM_N_BOARD, float $GOV_DRUG_N_MEDICINE, float $GOV_LAB_N_DIAGNOSTICS, float $GOV_OPERATING_ROOM_FEE, float $GOV_SUPPLIES, float $GOV_OTHERS, float $GOV_SUB_TOTAL, bool $GOV_PCSO, bool $GOV_DSWD, bool $GOV_DOH, bool $GOV_HMO, bool $GOV_LINGAP, float $P1_ROOM_N_BOARD, float $P1_DRUG_N_MEDICINE, float $P1_LAB_N_DIAGNOSTICS, float $P1_OPERATING_ROOM_FEE, float $P1_SUPPLIES, float $P1_OTHERS, float $P1_SUB_TOTAL, float $P2_ROOM_N_BOARD, float $P2_DRUG_N_MEDICINE, float $P2_LAB_N_DIAGNOSTICS, float $P2_OPERATING_ROOM_FEE, float $P2_SUPPLIES, float $P2_OTHERS, float $P2_SUB_TOTAL, float $OP_ROOM_N_BOARD, float $OP_DRUG_N_MEDICINE, float $OP_LAB_N_DIAGNOSTICS, float $OP_OPERATING_ROOM_FEE, float $OP_SUPPLIES, float $OP_OTHERS, float $OP_SUB_TOTAL, float $PROFESSIONAL_FEE_SUB_TOTAL, float $PROFESSIONAL_DISCOUNT_SUB_TOTAL, float $CHARGE_TOTAL, float $VAT_TOTAL, float $SP_TOTAL, float $GOV_TOTAL, float $P1_TOTAL, float $P2_TOTAL, float $OP_TOTAL, int $PREPARED_BY_ID, string $DATE_SIGNED, string $OTHER_NAME)
    {
        PhilHealth::where('ID', $ID)
            ->update([
                'CHARGES_ROOM_N_BOARD' => $CHARGES_ROOM_N_BOARD,
                'CHARGES_DRUG_N_MEDICINE' => $CHARGES_DRUG_N_MEDICINE,
                'CHARGES_LAB_N_DIAGNOSTICS' => $CHARGES_LAB_N_DIAGNOSTICS,
                'CHARGES_OPERATING_ROOM_FEE' => $CHARGES_OPERATING_ROOM_FEE,
                'CHARGES_SUPPLIES' => $CHARGES_SUPPLIES,
                'CHARGES_OTHERS' => $CHARGES_OTHERS,
                'CHARGES_SUB_TOTAL' => $CHARGES_SUB_TOTAL,
                'OTHER_SPECIFY' => $OTHER_SPECIFY,
                'VAT_ROOM_N_BOARD' => $VAT_ROOM_N_BOARD,
                'VAT_DRUG_N_MEDICINE' => $VAT_DRUG_N_MEDICINE,
                'VAT_LAB_N_DIAGNOSTICS' => $VAT_LAB_N_DIAGNOSTICS,
                'VAT_OPERATING_ROOM_FEE' => $VAT_OPERATING_ROOM_FEE,
                'VAT_SUPPLIES' => $VAT_SUPPLIES,
                'VAT_OTHERS' => $VAT_OTHERS,
                'VAT_SUB_TOTAL' => $VAT_SUB_TOTAL,
                'SP_ROOM_N_BOARD' => $SP_ROOM_N_BOARD,
                'SP_DRUG_N_MEDICINE' => $SP_DRUG_N_MEDICINE,
                'SP_LAB_N_DIAGNOSTICS' => $SP_LAB_N_DIAGNOSTICS,
                'SP_OPERATING_ROOM_FEE' => $SP_OPERATING_ROOM_FEE,
                'SP_SUPPLIES' => $SP_SUPPLIES,
                'SP_OTHERS' => $SP_OTHERS,
                'SP_SUB_TOTAL' => $SP_SUB_TOTAL,
                'GOV_ROOM_N_BOARD' => $GOV_ROOM_N_BOARD,
                'GOV_DRUG_N_MEDICINE' => $GOV_DRUG_N_MEDICINE,
                'GOV_LAB_N_DIAGNOSTICS' => $GOV_LAB_N_DIAGNOSTICS,
                'GOV_OPERATING_ROOM_FEE' => $GOV_OPERATING_ROOM_FEE,
                'GOV_SUPPLIES' => $GOV_SUPPLIES,
                'GOV_OTHERS' => $GOV_OTHERS,
                'GOV_SUB_TOTAL' => $GOV_SUB_TOTAL,
                'GOV_PCSO' => $GOV_PCSO,
                'GOV_DSWD' => $GOV_DSWD,
                'GOV_DOH' => $GOV_DOH,
                'GOV_HMO' => $GOV_HMO,
                'GOV_LINGAP' => $GOV_LINGAP,
                'P1_ROOM_N_BOARD' => $P1_ROOM_N_BOARD,
                'P1_DRUG_N_MEDICINE' => $P1_DRUG_N_MEDICINE,
                'P1_LAB_N_DIAGNOSTICS' => $P1_LAB_N_DIAGNOSTICS,
                'P1_OPERATING_ROOM_FEE' => $P1_OPERATING_ROOM_FEE,
                'P1_SUPPLIES' => $P1_SUPPLIES,
                'P1_OTHERS' => $P1_OTHERS,
                'P1_SUB_TOTAL' => $P1_SUB_TOTAL,
                'P2_ROOM_N_BOARD' => $P2_ROOM_N_BOARD,
                'P2_DRUG_N_MEDICINE' => $P2_DRUG_N_MEDICINE,
                'P2_LAB_N_DIAGNOSTICS' => $P2_LAB_N_DIAGNOSTICS,
                'P2_OPERATING_ROOM_FEE' => $P2_OPERATING_ROOM_FEE,
                'P2_SUPPLIES' => $P2_SUPPLIES,
                'P2_OTHERS' => $P2_OTHERS,
                'P2_SUB_TOTAL' => $P2_SUB_TOTAL,
                'OP_ROOM_N_BOARD' => $OP_ROOM_N_BOARD,
                'OP_DRUG_N_MEDICINE' => $OP_DRUG_N_MEDICINE,
                'OP_LAB_N_DIAGNOSTICS' => $OP_LAB_N_DIAGNOSTICS,
                'OP_OPERATING_ROOM_FEE' => $OP_OPERATING_ROOM_FEE,
                'OP_SUPPLIES' => $OP_SUPPLIES,
                'OP_OTHERS' => $OP_OTHERS,
                'OP_SUB_TOTAL' => $OP_SUB_TOTAL,
                'PROFESSIONAL_FEE_SUB_TOTAL' => $PROFESSIONAL_FEE_SUB_TOTAL,
                'PROFESSIONAL_DISCOUNT_SUB_TOTAL' => $PROFESSIONAL_DISCOUNT_SUB_TOTAL,
                'CHARGE_TOTAL' => $CHARGE_TOTAL,
                'VAT_TOTAL' => $VAT_TOTAL,
                'SP_TOTAL' => $SP_TOTAL,
                'GOV_TOTAL' => $GOV_TOTAL,
                'P1_TOTAL' => $P1_TOTAL,
                'P2_TOTAL' => $P2_TOTAL,
                'OP_TOTAL' => $OP_TOTAL,
                'PREPARED_BY_ID' => $PREPARED_BY_ID == 0 ? null : $PREPARED_BY_ID,
                'DATE_SIGNED' => $DATE_SIGNED == '' ? null : $DATE_SIGNED,
                'OTHER_NAME' => $OTHER_NAME ?? null,
            ]);
    }
    public function Delete(int $ID)
    {
        PhilhealthDrugsMedicines::where('PHILHEALTH_ID', $ID)->delete();
        PhilHealthProfFee::where('PHIC_ID', $ID)->delete();
        PhilHealth::where('ID', $ID)->delete();
    }
    public function Search($search, int $locationId, int $perPage, $ADMITTED, $DISCHARGED)
    {
        $result = PhilHealth::query()
            ->select([
                'philhealth.ID',
                'philhealth.RECORDED_ON',
                'philhealth.CODE',
                'philhealth.DATE',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.CHARGE_TOTAL',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                DB::raw('(select count(*) from service_charges inner join service_charges_items on service_charges_items.SERVICE_CHARGES_ID = service_charges.ID where service_charges_items.ITEM_ID = ' . $this->PHIL_HEALTH_ITEM_ID . ' and service_charges.LOCATION_ID = philhealth.LOCATION_ID  and service_charges.PATIENT_ID = philhealth.CONTACT_ID and service_charges.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL '),
                'philhealth.P1_TOTAL',
                'philhealth.PAYMENT_AMOUNT',
                'philhealth.AR_NO',
                'philhealth.AR_DATE',
                DB::raw('if(ISNULL(philhealth.AR_DATE),false,true)  as IN_PROGRESS')
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
                        ->orWhere('c.LAST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.FIRST_NAME', 'like', '%' . $search . '%')
                        ->orWhere('philhealth.AR_NO', 'like', '%' . $search . '%');
                });
            })
            ->when($ADMITTED, function ($query) use (&$ADMITTED) {
                $query->where('philhealth.DATE_ADMITTED', '>=', $ADMITTED);
            })
            ->when($DISCHARGED, function ($query) use (&$DISCHARGED) {
                $query->where('philhealth.DATE_DISCHARGED', '<=', $DISCHARGED);
            })
            ->where('IS_TEMP', '0')
            ->orderBy('philhealth.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function UpdateAR(int $ID, string $AR_NO, string $AR_DATE)
    {
        PhilHealth::where('ID', '=', $ID)
            ->update([
                'AR_NO' => $AR_NO == '' ? null : $AR_NO,
                'AR_DATE' => $AR_DATE == '' ? null : $AR_DATE
            ]);
    }
    public function IsExistsARNumber($AR_NO, $ID): bool
    {
        return PhilHealth::where('AR_NO', $AR_NO)->whereNot('ID', $ID)->exists();
    }
    public function PatientRecord($search, int $contact_id, int $perPage, int $LOCK_LOCATION_ID)
    {
        $result = PhilHealth::query()
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
                'philhealth.PAYMENT_AMOUNT',
                'philhealth.IS_TEMP'
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
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('philhealth.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->orderBy('philhealth.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function getProfFee($ID)
    {
        $result = PhilHealthProfFee::query()
            ->select([
                'philhealth_prof_fee.ID',
                'philhealth_prof_fee.CONTACT_ID',
                'philhealth_prof_fee.AMOUNT',
                'philhealth_prof_fee.DISCOUNT',
                'philhealth_prof_fee.FIRST_CASE',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN as PIN_NUM'

            ])
            ->selectRaw("CONCAT(SUBSTRING(c.PIN, 1, 4), '-', SUBSTRING(c.PIN, 5, 7), '-', SUBSTRING(c.PIN, 12, 1)) as PIN")

            ->join('contact as c', 'c.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('PHIC_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function getProfFeeFirst(int $PHIC_ID)
    {
        $result = PhilHealthProfFee::query()
            ->select([
                'philhealth_prof_fee.ID',
                'philhealth_prof_fee.CONTACT_ID',
                'philhealth_prof_fee.AMOUNT',
                'philhealth_prof_fee.DISCOUNT',
                'philhealth_prof_fee.FIRST_CASE',
                'philhealth_prof_fee.BILL_ID',
                'c.PRINT_NAME_AS as NAME',
                'c.PIN as PIN_NUM'
            ])
            ->selectRaw("CONCAT(SUBSTRING(c.PIN, 1, 4), '-', SUBSTRING(c.PIN, 5, 7), '-', SUBSTRING(c.PIN, 12, 1)) as PIN")
            ->join('contact as c', 'c.ID', '=', 'philhealth_prof_fee.CONTACT_ID')
            ->where('PHIC_ID', '=', $PHIC_ID)
            ->orderBy('LINE_NO', 'asc')
            ->first();

        return $result;
    }
    public function UpdatePFContact(int $PHIC_ID, int $NEW_CONTACT_ID)
    {
        PhilHealthProfFee::where('PHIC_ID', '=', $PHIC_ID)
            ->update([
                'CONTACT_ID' => $NEW_CONTACT_ID
            ]);
    }
    private function getLine($Id): int
    {
        return (int) PhilHealthProfFee::where('PHIC_ID', $Id)->max('LINE_NO');
    }
    public function StoreProfFee(int $PHIC_ID, int $CONTACT_ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        $this->CleanProfFee($PHIC_ID); // reset purspose

        $ID = $this->object->ObjectNextID('PHILHEALTH_PROF_FEE');
        $LINE_NO = $this->getLine($PHIC_ID) + 1;

        PhilHealthProfFee::create([
            'ID' => $ID,
            'PHIC_ID' => $PHIC_ID,
            'CONTACT_ID' => $CONTACT_ID,
            'AMOUNT' => $AMOUNT,
            'LINE_NO' => $LINE_NO,
            'DISCOUNT' => $DISCOUNT,
            'FIRST_CASE' => $FIRST_CASE
        ]);
    }
    public function UpdateProfFee(int $ID, float $AMOUNT, float $DISCOUNT, float $FIRST_CASE)
    {
        PhilHealthProfFee::where('ID', $ID)
            ->update([
                'AMOUNT' => $AMOUNT,
                'DISCOUNT' => $DISCOUNT,
                'FIRST_CASE' => $FIRST_CASE
            ]);
    }
    public function DeleteProfFee(int $ID)
    {
        PhilHealthProfFee::where('ID', $ID)->delete();
    }
    public function CleanProfFee(int $PHIC_ID)
    {

        PhilHealthProfFee::where('PHIC_ID', $PHIC_ID)->delete();
    }
    public function DrugMedicineStore(int $PHILHEALTH_ID, string $GENERIC_NAME, float $QUANTITY, string $DOSSAGE, string $ROUTE, string $FREQUENCY, float $TOTAL_COST, string $CONT_GENERIC_NAME, float $CONT_QUANTITY, string $CONT_DOSSAGE, string $CONT_ROUTE, string $CONT_FREQUENCY, float $CONT_TOTAL_COST)
    {
        $ID = $this->object->ObjectNextID('PHILHEALTH_DRUGS_MEDICINES');

        PhilhealthDrugsMedicines::create([
            'ID' => $ID,
            'PHILHEALTH_ID' => $PHILHEALTH_ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'GENERIC_NAME' => $GENERIC_NAME,
            'QUANTITY' => $QUANTITY,
            'DOSSAGE' => $DOSSAGE,
            'ROUTE' => $ROUTE,
            'FREQUENCY' => $FREQUENCY,
            'TOTAL_COST' => $TOTAL_COST,
            'CONT_GENERIC_NAME' => $CONT_GENERIC_NAME,
            'CONT_QUANTITY' => $CONT_QUANTITY,
            'CONT_DOSSAGE' => $CONT_DOSSAGE,
            'CONT_ROUTE' => $CONT_ROUTE,
            'CONT_FREQUENCY' => $CONT_FREQUENCY,
            'CONT_TOTAL_COST' => $CONT_TOTAL_COST

        ]);
    }
    public function DrugMedicineUpdate(int $ID, int $PHILHEALTH_ID, string $GENERIC_NAME, float $QUANTITY, string $DOSSAGE, string $ROUTE, string $FREQUENCY, float $TOTAL_COST, string $CONT_GENERIC_NAME, float $CONT_QUANTITY, string $CONT_DOSSAGE, string $CONT_ROUTE, string $CONT_FREQUENCY, float $CONT_TOTAL_COST)
    {
        PhilhealthDrugsMedicines::where('ID', $ID)
            ->update([
                'PHILHEALTH_ID' => $PHILHEALTH_ID,
                'GENERIC_NAME' => $GENERIC_NAME,
                'QUANTITY' => $QUANTITY,
                'DOSSAGE' => $DOSSAGE,
                'ROUTE' => $ROUTE,
                'FREQUENCY' => $FREQUENCY,
                'TOTAL_COST' => $TOTAL_COST,
                'CONT_GENERIC_NAME' => $CONT_GENERIC_NAME,
                'CONT_QUANTITY' => $CONT_QUANTITY,
                'CONT_DOSSAGE' => $CONT_DOSSAGE,
                'CONT_ROUTE' => $CONT_ROUTE,
                'CONT_FREQUENCY' => $CONT_FREQUENCY,
                'CONT_TOTAL_COST' => $CONT_TOTAL_COST

            ]);
    }
    public function DrugMedicineDelete(int $ID)
    {
        PhilhealthDrugsMedicines::where('ID', $ID, )->delete();
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
    public function UpdatePayment(int $PHILHEALTH_ID, float $TOTAL_PAY)
    {
        $data = $this->get($PHILHEALTH_ID);

        if ($data) {

            if ((float) $TOTAL_PAY >= (float) $data->P1_TOTAL) {
                $STATUS_ID = 11; // Paid
            } else {
                $STATUS_ID = 1; //PENDING
            }

            PhilHealth::where('ID', $PHILHEALTH_ID)
                ->update([
                    'PAYMENT_AMOUNT' => $TOTAL_PAY,
                    'STATUS_ID' => $STATUS_ID
                ]);
        }
    }

    public function DoctorPinformat(string $input): string
    {
        // Format the string
        $formatted = substr($input, 0, 4) . '-' . substr($input, 4, 7) . '-' . substr($input, 11, 1);
        return $formatted; // This will return: 1202-0500922-3

    }

    public function DropDownPhilHealth(int $PATIENT_ID, int $LOCATION_ID, int $PHILHEALTH_ID = 0): object
    {
        $result = PhilHealth::query()
            ->select([
                'ID',
                DB::raw("CONCAT(' SOA No.: ',CODE ,' /  Admitted:',DATE_ADMITTED ,'  /  Discharged:', DATE_DISCHARGED, '   / First Case Rate : ', format(P1_TOTAL,2) ) as NAME")
            ])
            ->where('CONTACT_ID', '=', $PATIENT_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->whereColumn('P1_TOTAL', '<>', 'PAYMENT_AMOUNT')
            ->when($PHILHEALTH_ID > 0, function ($query) use (&$PHILHEALTH_ID) {
                $query->orWhere('ID', '=', $PHILHEALTH_ID);
            })
            ->whereNotNull('AR_DATE')
            ->whereNotNull('AR_NO')
            ->get();

        return $result;
    }

    public function setUpdateTwoId(int $PHILHEALTH_ID, int $PATIENT_PAYMENT_ID, int $INVOICE_ID)
    {
        PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->update([
                'PATIENT_PAYMENT_ID' => $PATIENT_PAYMENT_ID,
                'INVOICE_ID' => $INVOICE_ID
            ]);
    }
    public function getPhilHealthIdbyPatientPayment(int $PATIENT_PAYMENT_ID): int
    {

        $data = PhilHealth::where('PATIENT_PAYMENT_ID', '=', $PATIENT_PAYMENT_ID)->first();
        if ($data) {

            return (int) $data->ID;
        }

        return 0;
    }
    public function getTwoId(int $PHILHEALTH_ID): array
    {

        $data = PhilHealth::select([
            'PATIENT_PAYMENT_ID',
            'INVOICE_ID'
        ])
            ->where('ID', '=', $PHILHEALTH_ID)
            ->first();

        if ($data) {
            return [
                'PATIENT_PAYMENT_ID' => $data->PATIENT_PAYMENT_ID ?? 0,
                'INVOICE_ID' => $data->INVOICE_ID ?? 0
            ];
        }

        return [];
    }
    public function Get_ID_by_INVOICE_ID(int $INVOICE_ID)
    {
        $data = PhilHealth::select(['ID'])
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        if ($data) {
            return (int) $data->ID;
        }
        return 0;
    }
    public function getDataByInvoiceId(int $INVOICE_ID)
    {
        $data = PhilHealth::query()
            ->where('INVOICE_ID', '=', $INVOICE_ID)
            ->first();

        if ($data) {
            return $data;
        }

        return null;
    }
    public function makePayableForDoctor(int $PHILHEALTH_ID, int $LOCATION_ID, string $DATE_BILL)
    {

        $data = PhilHealthProfFee::where('PHIC_ID', '=', $PHILHEALTH_ID)->whereNull('BILL_ID')->first();

        if ($data) {
            $DOCTOR_ID = $data->CONTACT_ID;
            $TERM_ID = 2;
            $DATE = $DATE_BILL;
            $DUE_DATE = $this->paymentTermServices->getDueDate(2, $DATE);
            $PAYABLE_ACCT_ID = 21;
            $AMOUNT = $data->FIRST_CASE ?? 0;
            $INPUT_TAX_ID = 14;
            $INPUT_TAX_ACCOUNT_ID = 28;
            $BILL_ID = $this->billingServices->Store(
                '',
                $DATE,
                $DOCTOR_ID,
                $LOCATION_ID,
                $TERM_ID,
                $DUE_DATE,
                '',
                0,
                '',
                $PAYABLE_ACCT_ID,
                $INPUT_TAX_ID,
                0,
                0,
                0,
                $INPUT_TAX_ACCOUNT_ID,
                15
            );
            $this->billingServices->ExpenseStore(
                $BILL_ID,
                $this->PROFESSIONAL_FEE_ACCOUNT_ID,
                $AMOUNT,
                0,
                0,
                0,
                '',
                0
            );
            $this->billingServices->ReComputed($BILL_ID);
            // 
            // Make Journal Entry;
            $bills = (int) $this->billingServices->object_type_map_bill;
            $billExpenses = (int) $this->billingServices->object_type_map_bill_expenses;

            $JOURNAL_NO = $this->accountJournalServices->getRecord($this->billingServices->object_type_map_bill, $BILL_ID);
            if ($JOURNAL_NO == 0) {
                $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->billingServices->object_type_map_bill, $BILL_ID) + 1;
            }

            $billCreditExpensesData = $this->billingServices->getBillExpenseJournal($BILL_ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billCreditExpensesData, $LOCATION_ID, $billExpenses, $DATE, "EXPENSE");
            //Main
            $billData = $this->billingServices->getBillJournal($BILL_ID);
            $this->accountJournalServices->JournalExecute($JOURNAL_NO, $billData, $LOCATION_ID, $bills, $DATE, "AP");

            PhilHealthProfFee::where('PHIC_ID', '=', $PHILHEALTH_ID)
                ->whereNull('BILL_ID')
                ->update(['BILL_ID' => $BILL_ID]);
        }
    }

    public function getListNonReceivedPFDoctor(int $DOCTOR_ID, int $LOCATION_ID, bool $IS_RECEIVED = false)
    {

        $result = DB::table('philhealth as p')
            ->select([
                'p.ID',
                'p.CODE',
                'p.DATE',
                'p.DATE_ADMITTED',
                'p.DATE_DISCHARGED',
                DB::raw('CONCAT( c.LAST_NAME, ", ", c.FIRST_NAME, ", ", LEFT(c.MIDDLE_NAME, 1) ) as PATIENT_NAME'),
                DB::raw('(select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = p.CONTACT_ID and hemodialysis.DATE between p.DATE_ADMITTED and p.DATE_DISCHARGED ) as NO_TREAT '),
                'pf.FIRST_CASE as AMOUNT',
                'p.PF_RECEIVED_DATE',

            ])

            ->join('contact as c', 'c.ID', 'p.CONTACT_ID')
            ->join('philhealth_prof_fee as pf', 'pf.PHIC_ID', '=', 'p.ID')
            ->join('location as l', 'l.ID', '=', 'p.LOCATION_ID')
            ->where('pf.CONTACT_ID', '=', $DOCTOR_ID)
            ->whereNotNull('p.INVOICE_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('p.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($IS_RECEIVED == true, function ($query) {
                $query->whereNotNull('PF_RECEIVED_DATE');
            })
            ->when($IS_RECEIVED == false, function ($query) {
                $query->whereNull('PF_RECEIVED_DATE');
            })
            ->get();

        return $result;
    }
    public function setReceived(int $PHILHEALTH_ID, $PF_RECEIVED_DATE)
    {
        PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->update([
                'PF_RECEIVED_DATE' => $PF_RECEIVED_DATE
            ]);
    }

    public function makeReceivableForCustomer(int $PHILHEALTH_ID): array
    {
        $exists = PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->whereNotNull('AR_NO')
            ->exists();

        if (!$exists) {
            return [
                'STATUS' => false,
                'MESSAGE' => 'AR No. not found.',
                'INVOICE_ID' => 0
            ];
        }

        $invoiceExists = PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->whereNotNull('INVOICE_ID')
            ->exists();

        if ($invoiceExists) {
            // invoice already created

            return [
                'STATUS' => false,
                'MESSAGE' => 'Successfully save but invoice already created',
                'INVOICE_ID' => 0
            ];
        }

        $data = $this->get($PHILHEALTH_ID);


        $DUE_DATE = (string) $this->paymentTermServices->getDueDate($this->TERM_ID, $data->AR_DATE);
        $ACCOUNT_RECEIVABLE_ID = 4;
        $OUTPUT_TAX_ID = 12;
        $OUTPUT_TAX_RATE = 0;
        $OUTPUT_TAX_VAT_METHOD = 0;
        $OUTPUT_TAX_ACCOUNT_ID = 28;

        $INVOICE_ID = (int) $this->invoiceServices->Store(
            '',
            $data->AR_DATE,
            $data->CONTACT_ID,
            $data->LOCATION_ID,
            0,
            0,
            $data->AR_NO,
            '',
            0,
            null,
            $this->TERM_ID,
            $DUE_DATE,
            null,
            0,
            '',
            $ACCOUNT_RECEIVABLE_ID,
            15,
            $OUTPUT_TAX_ID,
            $OUTPUT_TAX_RATE,
            $OUTPUT_TAX_VAT_METHOD,
            $OUTPUT_TAX_ACCOUNT_ID,
            $PHILHEALTH_ID
        );

        $QTY = (float) $this->getNumberOfTreatment(
            $data->CONTACT_ID,
            $data->LOCATION_ID,
            $data->DATE_ADMITTED,
            $data->DATE_DISCHARGED
        );


        $dataItem = $this->itemServices->get($this->PHIL_HEALTH_ITEM_ID);
        if ($dataItem) {
            $RATE = $this->priceLevelLineServices->GetPriceByLocation($data->LOCATION_ID, $this->PHIL_HEALTH_ITEM_ID);
            $AMOUNT = $RATE * $QTY;

            $taxRate = $this->taxServices->getRate($this->TAX_ID);
            $tax_result = $this->computeServices->ItemComputeTax($AMOUNT, $dataItem->TAXABLE, $this->TAX_ID, $taxRate);
            if ($tax_result) {
                $TAXABLE_AMOUNT = $tax_result['TAXABLE_AMOUNT'];
                $TAX_AMOUNT = $tax_result['TAX_AMOUNT'];
            }

            $this->invoiceServices->ItemStore(
                $INVOICE_ID,
                $this->PHIL_HEALTH_ITEM_ID,
                $QTY,
                0,
                1,
                $RATE,
                0,
                $AMOUNT,
                $dataItem->TAXABLE ?? false,
                $TAXABLE_AMOUNT,
                $TAX_AMOUNT,
                $dataItem->COGS_ACCOUNT_ID ?? 0,
                $dataItem->ASSET_ACCOUNT_ID ?? 0,
                $dataItem->GL_ACCOUNT_ID ?? 0,
                0,
                0,
                0,
                0,
                0,
                0
            );

            $this->invoiceServices->ReComputed($INVOICE_ID);
        }

        // JOURNAL
        $JOURNAL_NO = $this->accountJournalServices->getRecord($this->invoiceServices->object_type_invoice, $INVOICE_ID);
        if ($JOURNAL_NO == 0) {
            $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->invoiceServices->object_type_invoice, $INVOICE_ID) + 1;
        }

        //Main
        $invoiceData = $this->invoiceServices->getInvoiceJournal($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceData, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice, $data->AR_DATE);
        //Tax
        $invoiceDataTax = $this->invoiceServices->getInvoiceTaxJournal($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceDataTax, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice, $data->AR_DATE);
        //Income
        $invoiceItemData = $this->invoiceServices->getInvoiceItemJournalIncome($INVOICE_ID);
        $this->accountJournalServices->JournalExecute($JOURNAL_NO, $invoiceItemData, $data->LOCATION_ID, $this->invoiceServices->object_type_invoice_item, $data->AR_DATE);

        PhilHealth::where('ID', '=', $PHILHEALTH_ID)
            ->update([
                'INVOICE_ID' => $INVOICE_ID
            ]);

        return [
            'STATUS' => true,
            'MESSAGE' => 'Successfully save & invoice created',
            'INVOICE_ID' => $INVOICE_ID
        ];
    }


    public function getMonitor(int $YEAR, int $MONTH, int $locationId)
    {

        $result = PhilHealth::query()
            ->select([
                'philhealth.ID',
                'philhealth.RECORDED_ON',
                'philhealth.CODE',
                'philhealth.DATE',
                'philhealth.DATE_ADMITTED',
                'philhealth.DATE_DISCHARGED',
                'philhealth.CHARGE_TOTAL',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ' .', LEFT(c.MIDDLE_NAME, 1), IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                DB::raw('(select count(*) from hemodialysis inner join service_charges as sc on sc.DATE = hemodialysis.DATE and sc.PATIENT_ID = hemodialysis.CUSTOMER_ID and sc.LOCATION_ID = hemodialysis.LOCATION_ID  inner join service_charges_items as sci on sci.SERVICE_CHARGES_ID = sc.ID and sci.ITEM_ID = 2  where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as HEMO_TOTAL '),
                'philhealth.P1_TOTAL',
                'philhealth.PAYMENT_AMOUNT',
                'philhealth.AR_NO',
                'philhealth.AR_DATE',
                DB::raw('if(ISNULL(philhealth.AR_DATE),false,true)  as IN_PROGRESS'),
                DB::raw(" (select  GROUP_CONCAT(hemodialysis.DATE ORDER BY hemodialysis.DATE ASC SEPARATOR ', ') from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = philhealth.CONTACT_ID and hemodialysis.DATE between philhealth.DATE_ADMITTED and philhealth.DATE_DISCHARGED) as CONFINE_PERIOD "),
                DB::raw(" (select payment.DATE from payment_invoices 
                inner join payment on payment.ID = payment_invoices.PAYMENT_ID 
                inner join invoice on invoice.ID = payment_invoices.INVOICE_ID 
                inner join invoice_items on invoice_items.INVOICE_ID = invoice.ID
                 where invoice.TRANSACTION_REF_ID = philhealth.ID and invoice_items.ITEM_ID = '" . $this->PHIL_HEALTH_ITEM_ID . "'
                ) as PAID_DATE 
                 "),
                DB::raw("  (select payment.AMOUNT from payment_invoices 
                inner join payment on payment.ID = payment_invoices.PAYMENT_ID 
                inner join invoice on invoice.ID = payment_invoices.INVOICE_ID 
                inner join invoice_items on invoice_items.INVOICE_ID = invoice.ID
                 where invoice.TRANSACTION_REF_ID = philhealth.ID and invoice_items.ITEM_ID = '" . $this->PHIL_HEALTH_ITEM_ID . "'
                ) as PAID_AMOUNT 
                 "),
                DB::raw("  (select payment.RECEIPT_REF_NO from payment_invoices 
                inner join payment on payment.ID = payment_invoices.PAYMENT_ID 
                inner join invoice on invoice.ID = payment_invoices.INVOICE_ID 
                inner join invoice_items on invoice_items.INVOICE_ID = invoice.ID
                 where invoice.TRANSACTION_REF_ID = philhealth.ID and invoice_items.ITEM_ID = '" . $this->PHIL_HEALTH_ITEM_ID . "'
                ) as OR_NUMBER 
                 "),

                DB::raw(" (select tax_credit.AMOUNT from tax_credit_invoices 
                inner join tax_credit on tax_credit.ID = tax_credit_invoices.TAX_CREDIT_ID 
                inner join invoice on invoice.ID = tax_credit_invoices.INVOICE_ID 
                inner join invoice_items on invoice_items.INVOICE_ID = invoice.ID
                 where invoice.TRANSACTION_REF_ID = philhealth.ID and invoice_items.ITEM_ID = '" . $this->PHIL_HEALTH_ITEM_ID . "'
                )  as TAX_AMOUNT
                  "),
                DB::raw(" (select bill.AMOUNT from philhealth_prof_fee inner join bill on bill.ID = philhealth_prof_fee.BILL_ID  where philhealth_prof_fee.PHIC_ID =  philhealth.ID ) as DOCTOR_PF"),
                DB::raw(" (select bill.BALANCE_DUE from philhealth_prof_fee inner join bill on bill.ID = philhealth_prof_fee.BILL_ID  where philhealth_prof_fee.PHIC_ID =  philhealth.ID ) as DOCTOR_PF_BALANCE"),

            ])
            ->join('contact as c', 'c.ID', '=', 'philhealth.CONTACT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'philhealth.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'philhealth.STATUS_ID')
            ->where('IS_TEMP', '0')
            ->whereNotNull('philhealth.AR_DATE')
            ->when($YEAR > 0, function ($query) use (&$YEAR) {
                $query->whereYear('DATE_ADMITTED', '=', $YEAR)
                    ->whereYear('DATE_DISCHARGED', '=', $YEAR);
            })

            ->when($MONTH > 0, function ($query) use (&$MONTH) {
                $query->whereMonth('DATE_ADMITTED', '=', $MONTH)
                    ->whereMonth('DATE_DISCHARGED', '=', $MONTH);
            })
            ->orderBy('philhealth.AR_DATE', 'asc')
            ->orderBy('philhealth.LOCATION_ID', 'asc')
            ->get();

        return $result;
    }
}
