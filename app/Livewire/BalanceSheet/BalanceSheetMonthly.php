<?php

namespace App\Livewire\BalanceSheet;

use App\Services\AccountServices;
use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Component;

class BalanceSheetMonthly extends Component
{
    public $YEAR;
    public $LOCATION_ID;
    public $dataList = [];
    private $financialStatementServices;
    private $accountServices;
    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices, AccountServices $accountServices, NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->accountServices = $accountServices;
        $this->numberServices = $numberServices;
    }
    #[On('balance-sheet-monthly')]

    public function generate($result)
    {
        $this->dataList = [];
        $this->YEAR = $result['YEAR'];
        $this->LOCATION_ID = $result['LOCATION_ID'];

        $assetList = $this->financialStatementServices->getBalanceSheetAccountTypeListByMonth([0, 1, 2, 3, 4], $this->YEAR, $this->LOCATION_ID, false);
        $a[] = $this->SetData($assetList, 'Assets');
        $liabilityList = $this->financialStatementServices->getBalanceSheetAccountTypeListByMonth([5, 6, 7, 8], $this->YEAR, $this->LOCATION_ID, true);
        $l[] = $this->SetData($liabilityList, 'Liabilities');

        $JAN = $a[0]['JAN'] - $l[0]['JAN'];
        $FEB = $a[0]['FEB'] - $l[0]['FEB'];
        $MAR = $a[0]['MAR'] - $l[0]['MAR'];
        $APR = $a[0]['APR'] - $l[0]['APR'];
        $MAY = $a[0]['MAY'] - $l[0]['MAY'];
        $JUN = $a[0]['JUN'] - $l[0]['JUN'];
        $JUL = $a[0]['JUL'] - $l[0]['JUL'];
        $AUG = $a[0]['AUG'] - $l[0]['AUG'];
        $SEP = $a[0]['SEP'] - $l[0]['SEP'];
        $OCT = $a[0]['OCT'] - $l[0]['OCT'];
        $NOV = $a[0]['NOV'] - $l[0]['NOV'];
        $DEC = $a[0]['DEC'] - $l[0]['DEC'];
        $TOTAL = $a[0]['TOTAL'] - $l[0]['TOTAL'];


        $this->dataList[] = $this->getInsert(
            0,
            'Net Assets ',
            'total',
            $JAN != 0 ? $this->numberServices->AcctFormat($JAN) : '-',
            $FEB != 0 ? $this->numberServices->AcctFormat($FEB) : '-',
            $MAR != 0 ? $this->numberServices->AcctFormat($MAR) : '-',
            $APR != 0 ? $this->numberServices->AcctFormat($APR) : '-',
            $MAY != 0 ? $this->numberServices->AcctFormat($MAY) : '-',
            $JUN != 0 ? $this->numberServices->AcctFormat($JUN) : '-',
            $JUL != 0 ? $this->numberServices->AcctFormat($JUL) : '-',
            $AUG != 0 ? $this->numberServices->AcctFormat($AUG) : '-',
            $SEP != 0 ? $this->numberServices->AcctFormat($SEP) : '-',
            $OCT != 0 ? $this->numberServices->AcctFormat($OCT) : '-',
            $NOV != 0 ? $this->numberServices->AcctFormat($NOV) : '-',
            $DEC != 0 ? $this->numberServices->AcctFormat($DEC) : '-',
            $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
        );
        $this->equitySide();

    }

    private function equitySide()
    {
        $equityList = $this->financialStatementServices->getBalanceSheetAccountTypeListByMonth([9], $this->YEAR, $this->LOCATION_ID, true);
        $e = $this->SetData($equityList, 'Equity');


        $net_income = $this->financialStatementServices->getTotalNetIncome($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $this->CustomParameter('D', '', 'Net Income', $net_income);
        $RetainingEarnings = $this->HistoryRetainingEarnings($this->DATE_FROM);
        $this->CustomParameter('D', '', 'Retaining Earnings', $RetainingEarnings);
        $newEquty = $RetainingEarnings + $net_income + $e[0]['TOTAL'];
        $this->TotalParameter('HEADER', 'HEADER', 'TOTAL EQUITY', $newEquty);
    }
    private function SetData($list, string $title): array
    {


        $JAN = 0;
        $FEB = 0;
        $MAR = 0;
        $APR = 0;
        $MAY = 0;
        $JUN = 0;
        $JUL = 0;
        $AUG = 0;
        $SEP = 0;
        $OCT = 0;
        $NOV = 0;
        $DEC = 0;
        $TOTAL = 0;

        $T_JAN = 0;
        $T_FEB = 0;
        $T_MAR = 0;
        $T_APR = 0;
        $T_MAY = 0;
        $T_JUN = 0;
        $T_JUL = 0;
        $T_AUG = 0;
        $T_SEP = 0;
        $T_OCT = 0;
        $T_NOV = 0;
        $T_DEC = 0;
        $T_TOTAL = 0;

        $TMP = -1;
        $TMP_NAME = "";
        $this->dataList[] = $this->getInsert(0, $title, 'grand');
        foreach ($list as $data) {

            $JAN += $data->JAN;
            $FEB += $data->FEB;
            $MAR += $data->MAR;
            $APR += $data->APR;
            $MAY += $data->MAY;
            $JUN += $data->JUN;
            $JUL += $data->JUL;
            $AUG += $data->AUG;
            $SEP += $data->SEP;
            $OCT += $data->OCT;
            $NOV += $data->NOV;
            $DEC += $data->DEC;
            $TOTAL += $data->TOTAL;




            if ($TMP == -1) {
                $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                $TMP_NAME = $data->TYPE_NAME;
            } elseif ($TMP <> $data->TYPE) {
                $this->dataList[] = $this->getInsert(
                    0,
                    ' Total ' . $TMP_NAME,
                    'total',
                    $T_JAN != 0 ? $this->numberServices->AcctFormat($T_JAN) : '-',
                    $T_FEB != 0 ? $this->numberServices->AcctFormat($T_FEB) : '-',
                    $T_MAR != 0 ? $this->numberServices->AcctFormat($T_MAR) : '-',
                    $T_APR != 0 ? $this->numberServices->AcctFormat($T_APR) : '-',
                    $T_MAY != 0 ? $this->numberServices->AcctFormat($T_MAY) : '-',
                    $T_JUN != 0 ? $this->numberServices->AcctFormat($T_JUN) : '-',
                    $T_JUL != 0 ? $this->numberServices->AcctFormat($T_JUL) : '-',
                    $T_AUG != 0 ? $this->numberServices->AcctFormat($T_AUG) : '-',
                    $T_SEP != 0 ? $this->numberServices->AcctFormat($T_SEP) : '-',
                    $T_OCT != 0 ? $this->numberServices->AcctFormat($T_OCT) : '-',
                    $T_NOV != 0 ? $this->numberServices->AcctFormat($T_NOV) : '-',
                    $T_DEC != 0 ? $this->numberServices->AcctFormat($T_DEC) : '-',
                    $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-'
                );


                //CLEAR
                $T_JAN = 0;
                $T_FEB = 0;
                $T_MAR = 0;
                $T_APR = 0;
                $T_MAY = 0;
                $T_JUN = 0;
                $T_JUL = 0;
                $T_AUG = 0;
                $T_SEP = 0;
                $T_OCT = 0;
                $T_NOV = 0;
                $T_DEC = 0;
                $T_TOTAL = 0;



                $this->dataList[] = $this->getInsert(0, ' ' . $data->TYPE_NAME, 'total');
                $TMP_NAME = $data->TYPE_NAME;
            }
            $this->dataList[] = $this->getInsert(
                $data->ID,
                '   ' . $data->ACCOUNT_NAME,
                $data->TYPE_NAME,
                $data->JAN != 0 ? $this->numberServices->AcctFormat($data->JAN) : '-',
                $data->FEB != 0 ? $this->numberServices->AcctFormat($data->FEB) : '-',
                $data->MAR != 0 ? $this->numberServices->AcctFormat($data->MAR) : '-',
                $data->APR != 0 ? $this->numberServices->AcctFormat($data->APR) : '-',
                $data->MAY != 0 ? $this->numberServices->AcctFormat($data->MAY) : '-',
                $data->JUN != 0 ? $this->numberServices->AcctFormat($data->JUN) : '-',
                $data->JUL != 0 ? $this->numberServices->AcctFormat($data->JUL) : '-',
                $data->AUG != 0 ? $this->numberServices->AcctFormat($data->AUG) : '-',
                $data->SEP != 0 ? $this->numberServices->AcctFormat($data->SEP) : '-',
                $data->OCT != 0 ? $this->numberServices->AcctFormat($data->OCT) : '-',
                $data->NOV != 0 ? $this->numberServices->AcctFormat($data->NOV) : '-',
                $data->DEC != 0 ? $this->numberServices->AcctFormat($data->DEC) : '-',
                $data->TOTAL != 0 ? $this->numberServices->AcctFormat($data->TOTAL) : '-'
            );


            $T_JAN += $data->JAN;
            $T_FEB += $data->FEB;
            $T_MAR += $data->MAR;
            $T_APR += $data->APR;
            $T_MAY += $data->MAY;
            $T_JUN += $data->JUN;
            $T_JUL += $data->JUL;
            $T_AUG += $data->AUG;
            $T_SEP += $data->SEP;
            $T_OCT += $data->OCT;
            $T_NOV += $data->NOV;
            $T_DEC += $data->DEC;
            $T_TOTAL += $data->TOTAL;

            $TMP = (int) $data->TYPE;

        }
        if ($TMP_NAME <> '') {
            $this->dataList[] = $this->getInsert(
                0,
                ' Total ' . $TMP_NAME,
                'total',
                $T_JAN != 0 ? $this->numberServices->AcctFormat($T_JAN) : '-',
                $T_FEB != 0 ? $this->numberServices->AcctFormat($T_FEB) : '-',
                $T_MAR != 0 ? $this->numberServices->AcctFormat($T_MAR) : '-',
                $T_APR != 0 ? $this->numberServices->AcctFormat($T_APR) : '-',
                $T_MAY != 0 ? $this->numberServices->AcctFormat($T_MAY) : '-',
                $T_JUN != 0 ? $this->numberServices->AcctFormat($T_JUN) : '-',
                $T_JUL != 0 ? $this->numberServices->AcctFormat($T_JUL) : '-',
                $T_AUG != 0 ? $this->numberServices->AcctFormat($T_AUG) : '-',
                $T_SEP != 0 ? $this->numberServices->AcctFormat($T_SEP) : '-',
                $T_OCT != 0 ? $this->numberServices->AcctFormat($T_OCT) : '-',
                $T_NOV != 0 ? $this->numberServices->AcctFormat($T_NOV) : '-',
                $T_DEC != 0 ? $this->numberServices->AcctFormat($T_DEC) : '-',
                $T_TOTAL != 0 ? $this->numberServices->AcctFormat($T_TOTAL) : '-'
            );
        }


        //CLEAR
        $T_JAN = 0;
        $T_FEB = 0;
        $T_MAR = 0;
        $T_APR = 0;
        $T_MAY = 0;
        $T_JUN = 0;
        $T_JUL = 0;
        $T_AUG = 0;
        $T_SEP = 0;
        $T_OCT = 0;
        $T_NOV = 0;
        $T_DEC = 0;
        $T_TOTAL = 0;

        $this->dataList[] = $this->getInsert(
            0,
            'Total ' . $title,
            'grand',
            $JAN != 0 ? $this->numberServices->AcctFormat($JAN) : '-',
            $FEB != 0 ? $this->numberServices->AcctFormat($FEB) : '-',
            $MAR != 0 ? $this->numberServices->AcctFormat($MAR) : '-',
            $APR != 0 ? $this->numberServices->AcctFormat($APR) : '-',
            $MAY != 0 ? $this->numberServices->AcctFormat($MAY) : '-',
            $JUN != 0 ? $this->numberServices->AcctFormat($JUN) : '-',
            $JUL != 0 ? $this->numberServices->AcctFormat($JUL) : '-',
            $AUG != 0 ? $this->numberServices->AcctFormat($AUG) : '-',
            $SEP != 0 ? $this->numberServices->AcctFormat($SEP) : '-',
            $OCT != 0 ? $this->numberServices->AcctFormat($OCT) : '-',
            $NOV != 0 ? $this->numberServices->AcctFormat($NOV) : '-',
            $DEC != 0 ? $this->numberServices->AcctFormat($DEC) : '-',
            $TOTAL != 0 ? $this->numberServices->AcctFormat($TOTAL) : '-'
        );

        // return total
        return [
            'JAN' => $JAN,
            'FEB' => $FEB,
            'MAR' => $MAR,
            'APR' => $APR,
            'MAY' => $MAY,
            'JUN' => $JUN,
            'JUL' => $JUL,
            'AUG' => $AUG,
            'SEP' => $SEP,
            'OCT' => $OCT,
            'NOV' => $NOV,
            'DEC' => $DEC,
            'TOTAL' => $TOTAL
        ];
    }

    private function CustomParameter(string $order, string $type, string $Account, float $Amount)
    {
        if ($Amount > 0) {

            $this->dataList[] = [
                'ORDER' => $order,
                'TYPE' => $type,
                'ACCOUNT' => $Account,
                'AMOUNT' => $this->numberServices->Fixed($Amount)
            ];
        }
    }
    private function HeaderParameter(string $order, string $type, string $Account)
    {
        $this->dataList[] = [
            'ORDER' => $order,
            'TYPE' => $type,
            'ACCOUNT' => $Account,
            'AMOUNT' => ''
        ];
    }
    private function TotalParameter(string $order, string $type, string $Account, float $Amount)
    {


        $this->dataList[] = [
            'ORDER' => $order,
            'TYPE' => $type,
            'ACCOUNT' => $Account,
            'AMOUNT' => $this->numberServices->Fixed($Amount)
        ];
    }
    private function getInsert(int $ID, string $NAME, string $TYPE, string $JAN = '', string $FEB = '', string $MAR = '', string $APR = '', string $MAY = '', string $JUN = '', string $JUL = '', string $AUG = '', string $SEP = '', string $OCT = '', string $NOV = '', string $DEC = '', string $TOTAL = ''): array
    {

        return [
            'ACCOUNT_ID' => $ID,
            'ACCOUNT_NAME' => $NAME,
            'ACCOUNT_TYPE' => $TYPE,
            'JAN' => $JAN,
            'FEB' => $FEB,
            'MAR' => $MAR,
            'APR' => $APR,
            'MAY' => $MAY,
            'JUN' => $JUN,
            'JUL' => $JUL,
            'AUG' => $AUG,
            'SEP' => $SEP,
            'OCT' => $OCT,
            'NOV' => $NOV,
            'DEC' => $DEC,
            'TOTAL' => $TOTAL


        ];

    }
    public function render()
    {
        return view('livewire.balance-sheet.balance-sheet-monthly');
    }
}
