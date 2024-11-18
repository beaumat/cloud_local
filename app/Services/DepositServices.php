<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\DepositFunds;

class DepositServices
{
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
        $result = Deposit::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public function Store(string $CODE, string $DATE, int $BANK_ACCOUNT_ID, string $NOTES, int $CASH_BACK_ACCOUNT_ID, float $CASH_BACK_AMOUNT, string $CASH_BACK_NOTES, int $LOCATION_ID): int
    {

        $ID = $this->object->ObjectNextID('DEPOSIT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('DEPOSIT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Deposit::create([
            'ID'                    => $ID,
            'RECORDED_ON'           => $this->dateServices->Now(),
            'CODE'                  => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                  => $DATE,
            'BANK_ACCOUNT_ID'       => $BANK_ACCOUNT_ID,
            'AMOUNT'                => 0,
            'NOTES'                 => $NOTES,
            'CASH_BACK_ACCOUNT_ID'  => $CASH_BACK_ACCOUNT_ID,
            'CASH_BACK_AMOUNT'      => $CASH_BACK_AMOUNT,
            'CASH_BACK_NOTES'       => $CASH_BACK_NOTES,
            'LOCATION_ID'           => $LOCATION_ID,
            'STATUS'                => 0,
            'STATUS_DATE'           => $this->dateServices->NowDate()
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $BANK_ACCOUNT_ID, string $NOTES, int $CASH_BACK_ACCOUNT_ID, float $CASH_BACK_AMOUNT, string $CASH_BACK_NOTES,)
    {
        Deposit::where('ID', '=', $ID)
            ->update([
                'CODE'                  => $CODE,
                'BANK_ACCOUNT_ID'       => $BANK_ACCOUNT_ID,
                'NOTES'                 => $NOTES,
                'CASH_BACK_ACCOUNT_ID'  => $CASH_BACK_ACCOUNT_ID,
                'CASH_BACK_AMOUNT'      => $CASH_BACK_AMOUNT,
                'CASH_BACK_NOTES'       => $CASH_BACK_NOTES,
            ]);
    }
    public function Delete(int $ID)
    {
        Deposit::where('ID', '=', $ID)
            ->delete();
    }

    public function StatusUpdate(int $ID, int $STATUS)
    {
        Deposit::where('ID', '=', $ID)
            ->update([
                'STATUS'        => $STATUS,
                'STATUS_DATE'   => $this->dateServices->NowDate()
            ]);
    }

    public function Search($search, int $locationId, int $perPage)
    {
        return Deposit::query()
            ->select([
                'deposit.ID',
                'deposit.CODE',
                'deposit.DATE',
                'deposit.AMOUNT',
                'deposit.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'a.NAME as ACCOUNT_NAME'
            ])
            ->join('account as a', 'a.ID', '=', 'deposit.BANK_ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'deposit.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'deposit.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('deposit.CODE', 'like', '%' . $search . '%')
                        ->orWhere('deposit.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('deposit.NOTES', 'like', '%' . $search . '%')
                        ->orwhere('a.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('deposit.ID', 'desc')
            ->paginate($perPage);
    }
    public function StoreFund(int $DEPOSIT_ID, int $RECEIVED_FROM_ID = 0, int $ACCOUNT_ID, int $PAYMENT_METHOD_ID, string $CHECK_NO, float $AMOUNT, int $SOURCE_OBJECT_TYPE, int $SOURCE_OBJECT_ID)
    {

        $ID = $this->object->ObjectNextID('DEPOSIT_FUNDS');

        DepositFunds::create([
            'ID'                    => $ID,
            'DEPOSIT_ID'            => $DEPOSIT_ID,
            'RECEIVED_FROM_ID'      => $RECEIVED_FROM_ID > 0  ? $RECEIVED_FROM_ID : null,
            'ACCOUNT_ID'            => $ACCOUNT_ID,
            'PAYMENT_METHOD_ID'     => $PAYMENT_METHOD_ID,
            'CHECK_NO'              => $CHECK_NO,
            'AMOUNT'                => $AMOUNT,
            'SOURCE_OBJECT_TYPE'    => $SOURCE_OBJECT_TYPE,
            'SOURCE_OBJECT_ID'      => $SOURCE_OBJECT_ID
        ]);

        return $ID;
    }
    public function UpdateFund(int $ID, int $DEPOSIT_ID, int $RECEIVED_FROM_ID, string $CHECK_NO)
    {
        DepositFunds::where('ID', '=', $ID)
            ->where('DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->update([
                'RECEIVED_FROM_ID'      => $RECEIVED_FROM_ID > 0  ? $RECEIVED_FROM_ID : null,
                'CHECK_NO'              => $CHECK_NO
            ]);
    }
    public function DeleteFund(int $ID, int $DEPOSIT_ID,)
    {
        DepositFunds::where('ID', '=', $ID)
            ->where('DEPOSIT_ID', '=', $DEPOSIT_ID)
            ->delete();
    }
}
