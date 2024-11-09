<?php

namespace App\Services;

use App\Models\AccountReconciliation;
use App\Models\AccountReconciliationItems;

class BankReconServices
{
    private $object;
    private $dateServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->dateServices = $dateServices;
    }
    public function get($ID)
    {
        $result =   AccountReconciliation::where('ID', '=', $ID)->first();

        return $result;
    }
    public function Store(
        $CODE,
        string $DATE,
        int $ACCOUNT_ID,
        int $LOCATION_ID,
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        float $BEGINNING_BALANCE,
        float $CLEARED_DEPOSITS,
        float $CLEARED_WITHDRAWALS,
        float $CLEARED_BALANCE,
        string $NOTES,
        int $SC_ACCOUNT_ID,
        float $SC_RATE,
        int $IE_ACCOUNT_ID,
        float $IE_RATE
    ): int {

        $ID = $this->object->ObjectNextID('ACCOUNT_RECONCILIATION');
        AccountReconciliation::create(
            [
                'ID'                    => $ID,
                'RECORDED_ON'           => $this->dateServices->Now(),
                'DATE'                  => $DATE,
                'CODE'                  => $CODE,
                'ACCOUNT_ID'            => $ACCOUNT_ID,
                'LOCATION_ID'           => $LOCATION_ID,
                'PREVIOUS_ID'           => $PREVIOUS_ID,
                'SEQUENCE_NO'           => $SEQUENCE_NO,
                'BEGINNING_BALANCE'     => $BEGINNING_BALANCE,
                'CLEARED_DEPOSITS'      => $CLEARED_DEPOSITS,
                'CLEARED_WITHDRAWALS'   => $CLEARED_WITHDRAWALS,
                'CLEARED_BALANCE'       => $CLEARED_BALANCE,
                'ENDING_BALANCE'        => 0,
                'NOTES'                 => $NOTES,
                'STATUS'                => 0,
                'STATUS_DATE'           => $this->dateServices->NowDate(),
                'SC_ACCOUNT_ID'         => $SC_ACCOUNT_ID > 0  ? $SC_ACCOUNT_ID : null,
                'SC_RATE'               => $SC_RATE,
                'IE_ACCOUNT_ID'         => $IE_ACCOUNT_ID > 0 ? $IE_ACCOUNT_ID : null,
                'IE_RATE'               => $IE_RATE
            ]
        );


        return $ID;
    }
    public function Update(int $ID, string $DATE, string $CODE, string $NOTES, int $SC_ACCOUNT_ID, float $SC_RATE, int $IE_ACCOUNT_ID, float $IE_RATE)
    {

        AccountReconciliation::where('ID', '=', $ID)
            ->update([
                'DATE'                  => $DATE,
                'CODE'                  => $CODE,
                'NOTES'                 => $NOTES,
                'SC_ACCOUNT_ID'         => $SC_ACCOUNT_ID > 0  ? $SC_ACCOUNT_ID : null,
                'SC_RATE'               => $SC_RATE,
                'IE_ACCOUNT_ID'         => $IE_ACCOUNT_ID > 0 ? $IE_ACCOUNT_ID : null,
                'IE_RATE'               => $IE_RATE
            ]);
    }

    public function  UpdateAmount(int $ID, float $CLEARED_DEPOSITS, float $CLEARED_WITHDRAWALS, float $CLEARED_BALANCE, float $ENDING_BALANCE)
    {
        AccountReconciliation::where('ID', '=', $ID)
            ->update(attributes: [
                'CLEARED_DEPOSITS'      => $CLEARED_DEPOSITS,
                'CLEARED_WITHDRAWALS'   => $CLEARED_WITHDRAWALS,
                'CLEARED_BALANCE'       => $CLEARED_BALANCE,
                'ENDING_BALANCE'        => $ENDING_BALANCE,
            ]);
    }
    public function Delete(int $ID)
    {

        AccountReconciliationItems::where('ACCOUNT_RECONCILIATION_ID', '=', $ID)->delete();
        AccountReconciliation::where('ID', '=', $ID)->delete();
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {

        $result = AccountReconciliation::query()
            ->select([
                'account_reconciliation.ID',
                'account_reconciliation.CODE',
                'account_reconciliation.DATE',
                'a.NAME as ACCOUNT_NAME',
                'account_reconciliation.ENDING_BALANCE',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'account_reconciliation.NOTES'
            ])
            ->leftJoin('account as a', 'a.ID', '=', 'account_reconciliation.ACCOUNT_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'account_reconciliation.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'account_reconciliation.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('account_reconciliation.CODE', 'like', '%' . $search . '%')
                        ->orWhere('account_reconciliation.ENDING_BALANCE', 'like', '%' . $search . '%')
                        ->orWhere('account_reconciliation.NOTES', 'like', '%' . $search . '%')
                        ->orWhere('a.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('account_reconciliation.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function ItemStore() {
        
    }
    public function ItemUpdate() {}
    public function ItemDelete() {}
}
