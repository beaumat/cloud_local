<?php

namespace App\Services;

use App\Models\PaymentPeriod;

class PaymentPeriodServices
{


    private $object;

    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function Get($ID)
    {

        $result =  PaymentPeriod::where('ID', '=', $ID)->first();

        return $result;
    }
    public function GetYear(int $YEAR, int $LOCATION_ID)
    {
        $data = PaymentPeriod::query()
            ->select([
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO'
            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('DATE_FROM', '=', $YEAR)
            ->whereYear('DATE_TO', '=', $YEAR)
            ->get();

        return $data;
    }
    public function Store(string $RECEIPT_NO, int $LOCATION_ID, string $DATE_FROM, string $DATE_TO, float $TOTAL_PAYMENT, float $TOTAL_WTAX, int $BANK_ACCOUNT_ID)
    {
        $ID = (int) $this->object->ObjectNextID(TABLE_NAME: 'PAYMENT_PERIOD');

        PaymentPeriod::create([
            'ID'                => $ID,
            'RECEIPT_NO'        => $RECEIPT_NO,
            'LOCATION_ID'       => $LOCATION_ID,
            'DATE_FROM'         => $DATE_FROM,
            'DATE_TO'           => $DATE_TO,
            'TOTAL_PAYMENT'     => $TOTAL_PAYMENT,
            'TOTAL_WTAX'        => $TOTAL_WTAX,
            'BANK_ACCOUNT_ID'   => $BANK_ACCOUNT_ID
        ]);
    }
    public function Update(int $ID, string $RECEIPT_NO, string $DATE_FROM, string $DATE_TO)
    {
        PaymentPeriod::where('ID', '=', $ID)
            ->update([
                'RECEIPT_NO'    => $RECEIPT_NO,
                'DATE_FROM'     => $DATE_FROM,
                'DATE_TO'       => $DATE_TO,
            ]);
    }
    public function Delete(int $ID)
    {
        PaymentPeriod::where('ID', '=', $ID)->delete();
    }
    public function search($search, int $LOCATION_ID)
    {
        $result = PaymentPeriod::query()
            ->select([
                'ID',
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO'
            ])->where('LOCATION_ID', '=', $LOCATION_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where('RECEIPT_NO', 'like', '%' . $search . '%');
            })
            ->get();

        return $result;
    }
    public function List(int $LOCATION_ID)
    {
        $result = PaymentPeriod::query()
            ->select([
                'ID',
                'RECEIPT_NO',
                'DATE_FROM',
                'DATE_TO'
            ])
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->get();

        return $result;
    }
}
