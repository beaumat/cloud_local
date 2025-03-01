<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FinancialStatementServices
{
    private function getIncomeStatementAccountByType(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountType, bool $isCreditIncrease = false): object
    {
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
    public function getIncomeStatementAccountByTypeSum(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountType, bool $isCreditIncrease = true): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.TYPE', '=', $accountType)
            ->first()
            ->AMOUNT;

        return (float) $result;
    }
    public static function getIncomeStatementAccountByTypeSumArray(string $dateFrom, string $dateTo, int $LOCATION_ID, array $accountType = [], bool $isCreditIncrease = true): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $accountType)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }
    public static function getIncomeStatementAccountByIDSum(string $dateFrom, string $dateTo, int $LOCATION_ID, int $accountId, bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('a.ID', '=', $accountId)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }
    public function getIncomeStatementByMonth(int $ACCOUNT_ID, int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 1 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 2 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 3 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 4 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 5 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 6 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 7 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 8 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 9 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 10 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 11 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 12 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as `DEC`"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', $ACCOUNT_ID)
            ->whereYear('aj.OBJECT_DATE', $YEAR)
            ->first();

        return $result;
    }
    public function getIncomeStatementAccountTypeByMonth(array $TYPE = [], int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false, bool $isRunning = true)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $operation = $isRunning == true ? "<=" : "=";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 1 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 2 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 3 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 4 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 5 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 6 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 7 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 8 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 9 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 10 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 11 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 12 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as `DEC`"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $TYPE)
            ->when($isRunning, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '<=', $YEAR);
            })
            ->when(!$isRunning, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '=', $YEAR);
            })
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getIncomeStatementAccountTypeByDate(array $TYPE = [], string $dateFrom, string $dateTo, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $TYPE)
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }

    public static function getIncomeStatementAccountByIDSumArray(string $dateFrom, string $dateTo, int $LOCATION_ID, array $accountId = [], bool $isCreditIncrease = false): float
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ) as AMOUNT";
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw($sql),
            ])->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.ID', $accountId)
            ->first()
            ->AMOUNT;

        // Return the result or 0 if no data found
        return (float) $result;
    }
    public function IncomeAccount(string $dateFrom, string $dateTo, int $LOCATION_ID): object
    {
        $result = $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            10,
            true
        );

        return $result;
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
        $result = $this->getIncomeStatementAccountByType(
            $dateFrom,
            $dateTo,
            $LOCATION_ID,
            11,
            false
        );

        return $result;
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

    public function getTotalNetIncome(string $dateFrom, string $dateTo, int $LOCATION_ID): float
    {
        // Create a DateTime object from the input date string

        $IncomeSum = $this->SumIncomeAccount($dateFrom, $dateTo, $LOCATION_ID);

        $COGSSum = $this->SumCogsAccount($dateFrom, $dateTo, $LOCATION_ID);

        $gross_profit = $IncomeSum - $COGSSum;

        $ExpenseSum = $this->SumExpensesAccount($dateFrom, $dateTo, $LOCATION_ID);

        $operating_income = $gross_profit - $ExpenseSum;

        $OtherIncomeSum = $this->SumOtherIncomeAccount($dateFrom, $dateTo, $LOCATION_ID);

        $OtherExpenseSum = $this->SumOtherExpensesAccount($dateFrom, $dateTo, $LOCATION_ID);

        $net_other_income = $OtherIncomeSum - $OtherExpenseSum;

        $net_income = $operating_income + $net_other_income;

        return $net_income;
    }
    // Balance Sheet
    public function getBalanceSheetAccountByAcctType(string $dateFrom, string $dateTo, int $LOCATION_ID, array $AccountType, bool $isCreditIncrease = false, array $NotIncludeAccntID = []): object
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $sql = "sum( if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) -  if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT,0) ) as AMOUNT";

        $result = DB::table('account_journal as aj')
            ->select([
                'a.NAME as ACCOUNT_TITLE',
                DB::raw($sql),
                'at.DESCRIPTION as ACCOUNT_TYPE',
                'at.ACCOUNT_ORDER as ORDER'
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as at', 'at.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', '0')
            ->whereBetween('aj.OBJECT_DATE', [$dateFrom, $dateTo])
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('aj.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $AccountType)
            ->whereNotIn('a.ID', $NotIncludeAccntID)
            ->groupBy(['a.NAME', 'at.DESCRIPTION', 'at.ACCOUNT_ORDER'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getBalanceSheetAccountByAcctID(string $date, int $LOCATION_ID, array $AccountId, bool $isCreditIncrease = false)
    {
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
    public function getBalanceSheetAccuntByMonth(int $ACCOUNT_ID, int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $result = DB::table('account_journal as aj')
            ->select([
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 1 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 2 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 3 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 4 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 5 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 6 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 7 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 8 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 9 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 10 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 11 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) = 12 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as `DEC`"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->where('aj.ACCOUNT_ID', $ACCOUNT_ID)
            ->whereYear('aj.OBJECT_DATE', $YEAR)
            ->first();

        return $result;
    }
    public function getBalanceSheetAccountTypeListByMonth(array $type = [], int $YEAR, int $LOCATION_ID, bool $isCreditIncrease = false, bool $isRunning = true)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;
        $operation = $isRunning == true ? "<=" : "=";
        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 1 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as JAN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 2 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as FEB"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 3 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as MAR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 4 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)  ELSE 0 END) as APR"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 5 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as MAY"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 6 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUN"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 7 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as JUL"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 8 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as AUG"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 9 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as SEP"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 10 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as OCT"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 11 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as NOV"),
                DB::raw("SUM(CASE WHEN MONTH(aj.OBJECT_DATE) $operation 12 THEN if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0) ELSE 0 END) as `DEC`"),
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $type)
            ->when($isRunning, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '<=', $YEAR);
            })
            ->when(!$isRunning, function ($query) use (&$YEAR) {
                $query->whereYear('aj.OBJECT_DATE', '=', $YEAR);
            })
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getBalanceSheetAccountTypeListByDateRange(array $type = [], string $DATE_FROM, string $DATE_TO, int $LOCATION_ID, bool $isCreditIncrease = false)
    {
        $debit_is = $isCreditIncrease ? 1 : 0;
        $credit_is = $isCreditIncrease ? 0 : 1;

        $result = DB::table('account_journal as aj')
            ->select([
                'a.ID',
                'a.NAME as ACCOUNT_NAME',
                'a.TYPE',
                't.DESCRIPTION as TYPE_NAME',
                DB::raw("SUM(if(aj.ENTRY_TYPE = " . $debit_is . ", aj.AMOUNT,0) - if (aj.ENTRY_TYPE = " . $credit_is . ", aj.AMOUNT, 0)) as TOTAL")
            ])
            ->join('account as a', 'a.ID', '=', 'aj.ACCOUNT_ID')
            ->join('account_type_map as t', 't.ID', '=', 'a.TYPE')
            ->where('aj.AMOUNT', '>', 0)
            ->when($LOCATION_ID > 0, function ($query) use ($LOCATION_ID) {
                return $query->where('aj.LOCATION_ID', $LOCATION_ID);
            })
            ->whereIn('a.TYPE', $type)
            ->whereBetween('aj.OBJECT_DATE', [$DATE_FROM, $DATE_TO])
            ->groupBy(['a.ID', 'a.NAME', 'a.TYPE', 't.DESCRIPTION'])
            ->orderBy('a.TYPE')
            ->get();

        return $result;
    }
    public function getEquityRetainingEarningPrevious(string $Date, int $LOCATION_ID)
    {
    }
}
