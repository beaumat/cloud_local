<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FinancialStatementServices
{
    private function  getAccountByType(string $dateFrom, string $dateTo, int $LOCATION_ID, int $TYPE, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',

                DB::raw($sql),
            ])->leftJoin('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')

            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $TYPE)
            ->groupBy(['a.NAME'])
            ->get();

        return $result;
    }
    public function IncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getAccountByType($dateFrom, $dateTo, $LOCATION_ID, 10, true);
    }
    public function CogsAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getAccountByType($dateFrom, $dateTo, $LOCATION_ID, 11, false);
    }
    public function ExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getAccountByType($dateFrom, $dateTo, $LOCATION_ID, 12, false);
    }
    public function OtherIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            13,
            false
        );
    }
    public function OtherExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            14,
            false
        );
    }
}
