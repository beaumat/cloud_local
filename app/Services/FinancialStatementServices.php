<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FinancialStatementServices
{
    private function  getIncomeStatementAccountByType(
        string $dateFrom,
        string $dateTo,
        int $LOCATION_ID,
        int $accountType,
        bool $isCreditIncrease = false
    ) {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $accountType)
            ->groupBy(['a.NAME'])
            ->get();

        return $result;
    }
    private function  getIncomeStatementAccountByTypeSum(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountType, bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        // Use DB::raw with proper placeholders for debit/credit checks
        $sql = "SUM(IF(aj.ENTRY_TYPE = ?, aj.AMOUNT, 0) - IF(aj.ENTRY_TYPE = ?, aj.AMOUNT, 0)) AS AMOUNT";

        // Build the query
        $result = DB::table('account_journal as aj')
            ->select([
                'a.TYPE',
                DB::raw($sql),
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', 0)
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $accountType)
            ->setBindings([$debit_is, $credit_is]) // Bind values for the SQL placeholders
            ->first(); // Get the first record

        // Return the result or 0 if no data found
        return (float)  $result ? $result->AMOUNT : 0;
    }

    public function IncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            10,
            true
        );
    }
    public function SumIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            10,
            true
        );
    }
    public function CogsAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            11,
            false
        );
    }
    public function SumCogsAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            11,
            false
        );
    }
    public function ExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            12,
            false
        );
    }
    public function SumExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            12,
            false
        );
    }


    public function OtherIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            13,
            false
        );
    }
    public function SumOtherIncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            13,
            false
        );
    }
    public function OtherExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            14,
            false
        );
    }
    public function SumOtherExpensesAccount(string $dateFrom, string $dateTo, int $LOCATION_ID)
    {
        return $this->getIncomeStatementAccountByTypeSum(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            14,
            false
        );
    }

    // Balance Sheet
    public function getBalanceSheetAccountByAcctType(
        string $date,
        int $LOCATION_ID,
        array $AccountType,
        bool $isCreditIncrease = false,
        array $NotIncludeAccntID = []
    ) {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.OBJECT_DATE', '<=', $date)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $AccountType)
            ->whereNotIn('a.ID', $NotIncludeAccntID)
            ->groupBy(['a.NAME'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getBalanceSheetAccountByAcctID(
        string $date,
        int $LOCATION_ID,
        array $AccountId,
        bool $isCreditIncrease = false
    ) {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->where('aj.OBJECT_DATE', '<=', $date)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.ID', $AccountId)
            ->groupBy(['a.NAME'])
            ->get();

        return $result;
    }
}
