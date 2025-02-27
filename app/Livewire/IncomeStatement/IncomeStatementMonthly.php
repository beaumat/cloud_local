<?php

namespace App\Livewire\IncomeStatement;

use App\Services\AccountServices;
use App\Services\FinancialStatementServices;
use Livewire\Attributes\On;
use Livewire\Component;

class IncomeStatementMonthly extends Component
{

    public $dataList = [];
    public $LOCATION_ID;
    public $YEAR;
    private $financialStatementServices;
    private $accountServices;
    public function boot(FinancialStatementServices $financialStatementServices, AccountServices $accountServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->accountServices = $accountServices;
    }
    public function mount()
    {
        // REVENUE
        // COST
        // DATA


    }

    #[On('income-monthly')]
    public function generate($result)
    {

        $this->YEAR = (int) $result['YEAR'];
        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->dataList = [];
        $accountList = $this->accountServices->IncomeStatementMonthly();
        $TMP = 0;

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

        $G_JAN = 0;
        $G_FEB = 0;
        $G_MAR = 0;
        $G_APR = 0;
        $G_MAY = 0;
        $G_JUN = 0;
        $G_JUL = 0;
        $G_AUG = 0;
        $G_SEP = 0;
        $G_OCT = 0;
        $G_NOV = 0;
        $G_DEC = 0;
        $G_TOTAL = 0;



        foreach ($accountList as $list) {
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

            $data = $this->financialStatementServices->getIncomeStatementByMonth($list->ID, $this->YEAR, $this->LOCATION_ID);
            if ($data) {
                $JAN = $data->JAN >= 0 ? $data->JAN : $data->JAN * -1;
                $FEB = $data->FEB >= 0 ? $data->FEB : $data->FEB * -1;
                $MAR = $data->MAR >= 0 ? $data->MAR : $data->MAR * -1;
                $APR = $data->APR >= 0 ? $data->APR : $data->APR * -1;
                $MAY = $data->MAY >= 0 ? $data->MAY : $data->MAY * -1;
                $JUN = $data->JUN >= 0 ? $data->JUN : $data->JUN * -1;
                $JUL = $data->JUL >= 0 ? $data->JUL : $data->JUL * -1;
                $AUG = $data->AUG >= 0 ? $data->AUG : $data->AUG * -1;
                $SEP = $data->SEP >= 0 ? $data->SEP : $data->SEP * -1;
                $OCT = $data->OCT >= 0 ? $data->OCT : $data->OCT * -1;
                $NOV = $data->NOV >= 0 ? $data->NOV : $data->NOV * -1;
                $DEC = $data->DEC >= 0 ? $data->DEC : $data->DEC * -1;
                $TOTAL = $data->TOTAL >= 0 ? $data->TOTAL : $data->TOTAL * -1;
            }

            if ($TMP != $list->TYPE) {
                if ($TMP == 0) {
                    $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE($list->TYPE), 'total', '', '', '', '', '', '', '', '', '', '', '', '', '');
                } else {


                    if ($TMP == 11 || $TMP == 12 || $TMP == 14) {

                        $G_JAN -= $T_JAN;
                        $G_FEB -= $T_FEB;
                        $G_MAR -= $T_MAR;
                        $G_APR -= $T_APR;
                        $G_MAY -= $T_APR;
                        $G_JUN -= $T_JUN;
                        $G_JUL -= $T_JUL;
                        $G_AUG -= $T_AUG;
                        $G_SEP -= $T_SEP;
                        $G_OCT -= $T_OCT;
                        $G_NOV -= $T_NOV;
                        $G_DEC -= $T_DEC;
                        $G_TOTAL -= $T_TOTAL;

                    } else {
                        $G_JAN += $T_JAN;
                        $G_FEB += $T_FEB;
                        $G_MAR += $T_MAR;
                        $G_APR += $T_APR;
                        $G_MAY += $T_APR;
                        $G_JUN += $T_JUN;
                        $G_JUL += $T_JUL;
                        $G_AUG += $T_AUG;
                        $G_SEP += $T_SEP;
                        $G_OCT += $T_OCT;
                        $G_NOV += $T_NOV;
                        $G_DEC += $T_DEC;
                        $G_TOTAL += $T_TOTAL;
                    }



                    $this->dataList[] = $this->getInsert(
                        $list->ID,
                        'Total ' . $this->customTYPE($TMP),
                        'total',
                        number_format($T_JAN, 2),
                        number_format($T_FEB, 2),
                        number_format($T_MAR, 2),
                        number_format($T_APR, 2),
                        number_format($T_MAY, 2),
                        number_format($T_JUN, 2),
                        number_format($T_JUL, 2),
                        number_format($T_AUG, 2),
                        number_format($T_SEP, 2),
                        number_format($T_OCT, 2),
                        number_format($T_NOV, 2),
                        number_format($T_DEC, 2),
                        number_format($T_TOTAL, 2),
                    );

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

                    $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    if ($TMP == 13 && $list->TYPE == 12) {
                        // GROSS 
                        $this->dataList[] = $this->getInsert(
                            $list->ID,
                            $this->customTYPE(21),
                            'grand',
                            number_format($G_JAN, 2),
                            number_format($G_FEB, 2),
                            number_format($G_MAR, 2),
                            number_format($G_APR, 2),
                            number_format($G_MAY, 2),
                            number_format($G_JUN, 2),
                            number_format($G_JUL, 2),
                            number_format($G_AUG, 2),
                            number_format($G_SEP, 2),
                            number_format($G_OCT, 2),
                            number_format($G_NOV, 2),
                            number_format($G_DEC, 2),
                            number_format($G_TOTAL, 2),
                        );

                      

                        $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    }

                    $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE($list->TYPE), 'total', '', '', '', '', '', '', '', '', '', '', '', '', '');
                }




            }
            $TMP = $list->TYPE;
            $this->dataList[] = $this->getInsert(
                $list->ID,
                $list->NAME,
                $list->TYPE,
                $JAN != 0 ? number_format($JAN, 2) : '-',
                $FEB != 0 ? number_format($FEB, 2) : '-',
                $MAR != 0 ? number_format($MAR, 2) : '-',
                $APR != 0 ? number_format($APR, 2) : '-',
                $MAY != 0 ? number_format($MAY, 2) : '-',
                $JUN != 0 ? number_format($JUN, 2) : '-',
                $JUL != 0 ? number_format($JUL, 2) : '-',
                $AUG != 0 ? number_format($AUG, 2) : '-',
                $SEP != 0 ? number_format($SEP, 2) : '-',
                $OCT != 0 ? number_format($OCT, 2) : '-',
                $NOV != 0 ? number_format($NOV, 2) : '-',
                $DEC != 0 ? number_format($DEC, 2) : '-',
                $TOTAL != 0 ? number_format($TOTAL, 2) : '-'
            );



            $T_JAN += $JAN;
            $T_FEB += $FEB;
            $T_MAR += $MAR;
            $T_APR += $APR;
            $T_MAY += $APR;
            $T_JUN += $JUN;
            $T_JUL += $JUL;
            $T_AUG += $AUG;
            $T_SEP += $SEP;
            $T_OCT += $OCT;
            $T_NOV += $NOV;
            $T_DEC += $NOV;
            $T_TOTAL += $TOTAL;




        }
        $this->dataList[] = $this->getInsert(
            $list->ID,
            'Total ' . $this->customTYPE($TMP),
            'total',
            number_format($T_JAN, 2),
            number_format($T_FEB, 2),
            number_format($T_MAR, 2),
            number_format($T_APR, 2),
            number_format($T_MAY, 2),
            number_format($T_JUN, 2),
            number_format($T_JUL, 2),
            number_format($T_AUG, 2),
            number_format($T_SEP, 2),
            number_format($T_OCT, 2),
            number_format($T_NOV, 2),
            number_format($T_DEC, 2),
            number_format($T_TOTAL, 2),
        );


        $this->dataList[] = $this->getInsert(0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');


        $this->dataList[] = $this->getInsert(
            $list->ID,
            $this->customTYPE(22),
            'grand',
            number_format($G_JAN, 2),
            number_format($G_FEB, 2),
            number_format($G_MAR, 2),
            number_format($G_APR, 2),
            number_format($G_MAY, 2),
            number_format($G_JUN, 2),
            number_format($G_JUL, 2),
            number_format($G_AUG, 2),
            number_format($G_SEP, 2),
            number_format($G_OCT, 2),
            number_format($G_NOV, 2),
            number_format($G_DEC, 2),
            number_format($G_TOTAL, 2),
        );

    }
    private function customTYPE(int $ID)
    {
        switch ($ID) {
            case 10:
                return 'Trading Income';

            case 11:
                return 'Cost of Sales';

            case 12:
                return 'Operating Expenses';

            case 13:
                return 'Other Income';

            case 14:
                return 'Other Expenses';

            case 21:
                return 'Gross Profit';

            case 22:
                return 'Net Profit';

        }
    }
    private function getInsert(
        int $ID,
        string $NAME,
        string $TYPE,
        string $JAN,
        string $FEB,
        string $MAR,
        string $APR,
        string $MAY,
        string $JUN,
        string $JUL,
        string $AUG,
        string $SEP,
        string $OCT,
        string $NOV,
        string $DEC,
        string $TOTAL
    ): array {
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
    public function openAccountDetails() {
        
    }
    public function render()
    {
        return view('livewire.income-statement.income-statement-monthly');
    }
}
