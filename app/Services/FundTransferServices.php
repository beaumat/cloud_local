<?php

namespace App\Services;

use App\Models\FundTransfer;

class FundTransferServices
{
    public int $object_type_id = 93; // 
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
        $result = FundTransfer::where('ID', '=', $ID)->first();
        return $result;
    }
    public function Store($DATE, string $CODE, int $FROM_ACCOUNT_ID, int $TO_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT): int
    {

        $ID = (int) $this->object->ObjectNextID('FUND_TRANSFER');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('FUND_TRANSFER');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        FundTransfer::create([
            'ID'                        => $ID,
            'RECORDED_ON'               => $this->dateServices->Now(),
            'DATE'                      => $DATE,
            'CODE'                      => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $FROM_LOCATION_ID : null),
            'FROM_ACCOUNT_ID'           => $FROM_ACCOUNT_ID,
            'TO_ACCOUNT_ID'             => $TO_ACCOUNT_ID,
            'FROM_NAME_ID'              => $FROM_NAME_ID,
            'TO_NAME_ID'                => $TO_NAME_ID,
            'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
            'TO_LOCATION_ID'            => $TO_LOCATION_ID,
            'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
            'CLASS_ID'                  => null,
            'AMOUNT'                    => $AMOUNT,
            'NOTES'                     => $NOTES
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $FROM_ACCOUNT_ID, int $TO_ACCOUNT_ID, int $FROM_NAME_ID, int $TO_NAME_ID, int $FROM_LOCATION_ID, int $TO_LOCATION_ID, int $INTER_LOCATION_ACCOUNT_ID, string $NOTES, float $AMOUNT)
    {

        FundTransfer::where('ID', '=', $ID)
            ->update([
                'ID'                        => $ID,
                'CODE'                      => $CODE,
                'FROM_ACCOUNT_ID'           => $FROM_ACCOUNT_ID,
                'TO_ACCOUNT_ID'             => $TO_ACCOUNT_ID,
                'FROM_NAME_ID'              => $FROM_NAME_ID > 0 ? $FROM_NAME_ID : null,
                'TO_NAME_ID'                => $TO_NAME_ID > 0 ? $TO_NAME_ID : null,
                'FROM_LOCATION_ID'          => $FROM_LOCATION_ID,
                'TO_LOCATION_ID'            => $TO_LOCATION_ID,
                'INTER_LOCATION_ACCOUNT_ID' => $INTER_LOCATION_ACCOUNT_ID > 0 ? $INTER_LOCATION_ACCOUNT_ID : null,
                'CLASS_ID'                  => null,
                'NOTES'                     => $NOTES,
                'AMOUNT'                    => $AMOUNT
            ]);
    }
    public function Delete(int $ID)
    {
        FundTransfer::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $locationId)
    {

        $result = FundTransfer::query()
            ->select([
                'fund_transfer.ID',
                'fund_transfer.CODE',
                'fund_transfer.DATE',
                'l.NAME as LOCATION_FROM',
                't.NAME as LOCATION_TO',
                'c.NAME as FROM_NAME',
                'ct.NAME as TO_NAME',
                'fund_transfer.NOTES',
            ])
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'fund_transfer.FROM_LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('location as t', 't.ID', '=', 'fund_transfer.TO_LOCATION_ID')
            ->leftJoin('contact as c', 'c.ID', '=', 'fund_transfer.FROM_NAME_ID')
            ->leftJoin('contact as ct', 'c.ID', '=', 'fund_transfer.TO_NAME_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('fund_transfer.CODE', 'like', '%' . $search . '%')
                        ->orWhere('fund_transfer.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('fund_transfer.ID', 'desc')
            ->paginate(30);

        return $result;
    }
}
