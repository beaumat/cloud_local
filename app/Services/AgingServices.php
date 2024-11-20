<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class AgingServices
{
    public function __construct() {}


    private function DueAging() {}


    public function AccountsReceivables(string $AS_OF_DATE, int $LOCATION_ID, int $CONTACT_ID)
    {
        $result = Invoice::query()
            ->select([
                'invoice.ID',
                'invoice.CODE',
                'invocie.DATE',
                'invoice.CUSTOMER_ID',
                'invoice.AMOUNT',
                'invoice.BALANCE_DUE',
                'c.NAME as CONTACT_NAME',
                'invoice.DUE_DATE'
            ])
            ->join('contact as c', 'c.ID', '=', 'invoice.CUSTOMER_ID')
            ->where('invoice.DUE_DATE', '>=', $AS_OF_DATE)
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('invoice.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->when($CONTACT_ID > 0, function ($query) use (&$CONTACT_ID) {
                $query->where('invoice.CONTACT_ID', '=', $CONTACT_ID);
            })
            ->get();

        return $result;
    }
    public function ARAgingSummary(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result = DB::table('contact as c')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) <= 0 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_CURRENT "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 1 AND 30 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_1_30 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 31 AND 60 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_31_60 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) BETWEEN 61 AND 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_61_90 "),
                DB::raw(" SUM(CASE WHEN DATEDIFF('$AS_OF_DATE',i.DUE_DATE) > 90 THEN i.BALANCE_DUE ELSE 0 END) AS DUE_90_OVER "),
                DB::raw(" SUM(i.BALANCE_DUE) AS BALANCE"),
            ])
            ->leftJoin('invoice as i', function ($join) {
                $join->on('i.CUSTOMER_ID', '=', 'c.ID');
            })
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('c.INACTIVE', '=', 0)
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereIn('c.TYPE', [1, 3])
            ->groupBy('c.ID', 'c.NAME')
            ->having('BALANCE', '>', 0)
            ->get();

        return $result;
    }
    public function ARAgingDetais(string $AS_OF_DATE, int $LOCATION_ID, array $CONTACT_SELECT)
    {
        $result =  DB::table('invoice as i')
            ->select([
                'c.ID as CONTACT_ID',
                'c.NAME as CONTACT_NAME',
                DB::raw(" DATEDIFF('$AS_OF_DATE',i.DUE_DATE) as AGING"),
                'i.DATE',
                'i.CODE',
                'i.DUE_DATE',
                'i.AMOUNT',
                'i.BALANCE_DUE',
                't.DESCRIPTION as PAYMENT_TERMS',
                'l.NAME as LOCATION_NAME'

            ])
            ->join('contact as c', 'c.ID', '=', 'i.CUSTOMER_ID')
            ->join('payment_terms as t', 't.ID', '=', 'i.PAYMENT_TERMS_ID')
            ->join('location as l', 'l.ID', '=', 'i.LOCATION_ID')
            ->when($LOCATION_ID > 0, function ($query) use (&$LOCATION_ID) {
                $query->where('i.LOCATION_ID', '=', $LOCATION_ID);
            })
            ->where('c.INACTIVE', '=', 0)
            ->when($CONTACT_SELECT, function ($query) use (&$CONTACT_SELECT) {
                $query->whereIn('c.ID', $CONTACT_SELECT);
            })
            ->whereIn('c.TYPE', [1, 3])
            ->where('i.BALANCE_DUE', '>', 0)
            ->orderBy('DUE_DATE', 'desc')
            ->get();

        return $result;
    }
    public function CustomerBalance() {}
}
