<?php

namespace App\Services;

use App\Models\Check;

class WriteCheckServices
{
    private int $CHECK_TYPE_ID = 0;
    private $object;
    private $dateServices;
    private $systemSettingServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices)
    {
        $this->object = $objectServices;
        $this->dateServices = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
    }
    public function Get(int $ID)
    {
        return Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $BANK_ACCOUNT_ID,
        int $PAY_TO_ID,
        int $LOCATION_ID,
        string $NOTES,
        int $ACCOUNTS_PAYABLE_ID
    ): int {
        $ID = (int) $this->object->ObjectNextID('CHECK');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('CHECK');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Check::create([
            'ID'                => $ID,
            'RECORDED_ON'       => $this->dateServices->Now(),
            'CODE'              => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'              => $DATE,
            'TYPE'              => $this->CHECK_TYPE_ID,
            'BANK_ACCOUNT_ID'   => $BANK_ACCOUNT_ID,
            'PAY_TO_ID'         => $PAY_TO_ID,
            'LOCATION_ID'       => $LOCATION_ID,
            'AMOUNT'            => 0,
            'NOTES'             => $NOTES,
            'PRINTED'           => false,
            'STATUS'            => 0,
            'STATUS_DATE'       => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID ?? null
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Check::where('ID', $ID)
            ->update([
                'STATUS'        => $STATUS,
                'STATUS_DATE'   => $this->dateServices->NowDate()
            ]);
    }
    public function Update(int $ID, string $CODE, int $BANK_ACCOUNT_ID, int $PAY_TO_ID, int $LOCATION_ID, float $AMOUNT, string $NOTES)
    {
        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->update([
                'ID'                => $ID,
                'CODE'              => $CODE,
                'BANK_ACCOUNT_ID'   => $BANK_ACCOUNT_ID,
                'PAY_TO_ID'         => $PAY_TO_ID,
                'LOCATION_ID'       => $LOCATION_ID,
                'AMOUNT'            => $AMOUNT,
                'NOTES'             => $NOTES,
                'PRINTED'           => false
            ]);
    }
    public function Delete(int $ID)
    {
        Check::where('ID', $ID)
            ->where('TYPE', '=', $this->CHECK_TYPE_ID)
            ->delete();
    }
    public function Search($search, $locationId, $perPage)
    {
        $result = Check::query()
            ->select([
                'check.ID',
                'check.CODE',
                'check.DATE',
                'check.AMOUNT',
                'check.NOTES',
                'c.NAME as CONTACT_NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as BANK_ACCOUNT_NAME'
            ])
            ->join('contact as c', 'c.ID', '=', 'check.PAY_TO_ID')
            ->join('account as a', 'a.ID', '=', 'check.BANK_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'check.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'check.STATUS')
            ->where('check.TYPE', '=', $this->CHECK_TYPE_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('check.CODE', 'like', '%' . $search . '%')
                        ->orWhere('check.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('check.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('check.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
}
