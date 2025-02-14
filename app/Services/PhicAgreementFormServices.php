<?php

namespace App\Services;

use App\Models\PhicAgreementFormDetails;
use App\Models\PhicAgreementFormTitle;

class PhicAgreementFormServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function getTitleByType(int $TYPE)
    {

        $result = PhicAgreementFormTitle::query()
            ->select([
                'ID',
                'LINE',
                'DESCRIPTION'
            ])
            ->where("TYPE", '=', $TYPE)
            ->orderBy('LINE', 'asc')
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
    public function getTitleByID(int $ID)
    {

        $result = PhicAgreementFormTitle::query()
            ->select([
                'ID',
                'LINE',
                'DESCRIPTION'
            ])
            ->where("ID", '=', $ID)
            ->first();

        return $result;
    }
    public function storeDetails(int $HEMO_ID, int $PHIC_AFT_ID, bool $IS_CHECK)
    {
        $ID = (int) $this->object->ObjectNextID('PHIC_AGREEMENT_FORM_DETAILS');

        PhicAgreementFormDetails::create([
            'ID' => $ID,
            'HEMO_ID' => $HEMO_ID,
            'PHIC_AFT_ID' => $PHIC_AFT_ID,
            'IS_CHECK' => $IS_CHECK

        ]);
    }
    public function updateDetails(int $HEMO_ID, int $PHIC_AFT_ID, bool $IS_CHECK)
    {
        PhicAgreementFormDetails::where('HEMO_ID', '=', $HEMO_ID)
            ->where('PHIC_AFT_ID', '=', $PHIC_AFT_ID)
            ->update([
                'IS_CHECK' => $IS_CHECK
            ]);
    }


}