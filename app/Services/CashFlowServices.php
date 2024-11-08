<?php

namespace App\Services;

use App\Models\CashFlowDetails;
use App\Models\CashFlowHeader;
use App\Models\CashFlowKey;

class CashFlowServices
{

    private $dateServices;
    private $financialStatementServices;
    public function __construct(DateServices $dateServices, FinancialStatementServices $financialStatementServices)
    {
        $this->dateServices = $dateServices;
        $this->financialStatementServices = $financialStatementServices;
    }
    public function ACCOUNT_BASE_LIST()
    {
        return [
            ['ID' => 0, 'NAME' => 'ACCOUNT_ID'],
            ['ID' => 1, 'NAME' => 'ACCOUNT_TYPE'],
            ['ID' => 2, 'NAME' => 'ACCOUNT_IN'],
            ['ID' => 3, 'NAME' => 'ACCOUNT_TYPE_IN'],
            ['ID' => 4, 'NAME' => 'I/(D) in Cash'],
            ['ID' => 5, 'NAME' => 'Cash End on Last Year']

        ];
    }
    private function getHeader_LINE_NO(int $LOCATION_ID): int
    {
        return (int) CashFlowHeader::where('LOCATION_ID', '=', $LOCATION_ID)
            ->max('LINE_NO');
    }
    public static function getHeader(int $ID)
    {
        $result = CashFlowHeader::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public static function getHeaderAmount(int $ID, float $AMOUNT): float
    {
        $data =  self::getHeader($ID);
        if ($data) {
            if ($data->NAME == "END") {
                return $AMOUNT;
            }
            return 0;
        }

        return 0;
    }
    public function StoreHeader(string $NAME, int $LOCATION_ID, int $LINE_NO, bool $INACTIVE = false)
    {
        CashFlowHeader::create([
            'NAME'          => $NAME,
            'LOCATION_ID'   => $LOCATION_ID,
            'LINE_NO'       => $LINE_NO > 0 ? $LINE_NO : $this->getHeader_LINE_NO($LOCATION_ID) + 1,
            'INACTIVE'      => $INACTIVE,
            'RECORDED_ON'   => $this->dateServices->Now()
        ]);
    }
    public function UpdateHeader(int $ID, string $NAME, int $LINE_NO, bool $INACTIVE)
    {

        CashFlowHeader::where('ID', '=', $ID)
            ->update([
                'NAME'      => $NAME,
                'LINE_NO'   => $LINE_NO,
                'INACTIVE'  => $INACTIVE
            ]);
    }

    public function DeleteHeader(int $ID)
    {
        CashFlowHeader::where('ID', '=', $ID)
        ->delete();
    }
    public static function GetHeaderList(int $LOCATION_ID)
    {
        $result = CashFlowHeader::where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    private function getDetails_LINE_NO(int $CF_HEADER_ID): int
    {
        return (int) CashFlowDetails::where('CF_HEADER_ID', '=', $CF_HEADER_ID)
            ->max('LINE_NO');
    }
    public static function GetDetails(int $ID)
    {
        $result = CashFlowDetails::where('ID', '=', $ID)->first();
        return $result;
    }
    public function StoreDetails(int $CF_HEADER_ID, string $NAME, int $LINE_NO, bool $IS_TOTAL)
    {
        CashFlowDetails::create([
            'CF_HEADER_ID'  => $CF_HEADER_ID,
            'NAME'          => $NAME,
            'LINE_NO'       => $LINE_NO > 0 ? $LINE_NO : $this->getDetails_LINE_NO($CF_HEADER_ID) + 1,
            'RECORDED_ON'   => $this->dateServices->Now(),
            'INACTIVE'      => false,
            'IS_TOTAL'      => $IS_TOTAL
        ]);
    }
    public function UpdateDetails(int $ID, string $NAME, int $LINE_NO, bool $INACTIVE, bool $IS_TOTAL)
    {
        CashFlowDetails::where('ID', '=',  $ID)
            ->update([
                'NAME'      => $NAME,
                'LINE_NO'   => $LINE_NO,
                'INACTIVE'  => $INACTIVE,
                'IS_TOTAL'  => $IS_TOTAL
            ]);
    }
    public function DeleteDetails(int $ID)
    {
        CashFlowDetails::where('ID', '=',  $ID)->delete();
    }
    public static function GetDetailList(int $CF_HEADER_ID)
    {
        $result = CashFlowDetails::where('CF_HEADER_ID', '=', $CF_HEADER_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    private function getKey_LINE_NO(int $CS_FLOW_DETAILS_ID): int
    {
        return (int) CashFlowKey::where('CS_FLOW_DETAILS_ID', '=', $CS_FLOW_DETAILS_ID)
            ->max('LINE_NO');
    }
    public function StoreKey(int $CS_FLOW_DETAILS_ID, int $ACCOUNT_BASE, string $ACCOUNT_KEY, bool $DEBIT_DEFAULT, int $LINE_NO, string $NAME)
    {

        CashFlowKey::create([
            'ACCOUNT_BASE'          => $ACCOUNT_BASE,
            'ACCOUNT_KEY'           => $ACCOUNT_KEY,
            'DEBIT_DEFAULT'         => $DEBIT_DEFAULT,
            'LINE_NO'               => $LINE_NO  > 0 ? $LINE_NO : $this->getKey_LINE_NO($CS_FLOW_DETAILS_ID) + 1,
            'INACTIVE'              => false,
            'CS_FLOW_DETAILS_ID'    => $CS_FLOW_DETAILS_ID,
            'NAME'                  => $NAME
        ]);
    }
    public function UpdateKey(int $ID, int $ACCOUNT_BASE, string $ACCOUNT_KEY, bool $DEBIT_DEFAULT, int $LINE_NO, bool $INACTIVE, string $NAME)
    {
        CashFlowKey::where('ID', $ID)
            ->update([
                'ACCOUNT_BASE'  => $ACCOUNT_BASE,
                'ACCOUNT_KEY'   => $ACCOUNT_KEY,
                'DEBIT_DEFAULT' => $DEBIT_DEFAULT,
                'LINE_NO'       => $LINE_NO,
                'INACTIVE'      => $INACTIVE,
                'NAME'          => $NAME
            ]);
    }
    public function DeleteKey(int $ID)
    {
        CashFlowKey::where('ID', '=', $ID)->delete();
    }
    public static function GetKeyList(int $CS_FLOW_DETAILS_ID)
    {
        $result =  CashFlowKey::where('CS_FLOW_DETAILS_ID', '=', $CS_FLOW_DETAILS_ID)
            ->where('INACTIVE', '=', false)
            ->orderBy('LINE_NO')
            ->get();

        return $result;
    }
    public static function GetKey(int $ID)
    {
        $result = CashFlowKey::where('ID', '=', $ID)->first();

        return $result;
    }


}
