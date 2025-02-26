<?php

namespace App\Livewire\IncomeStatement;

use App\Services\AccountServices;
use App\Services\FinancialStatementServices;
use Livewire\Attributes\On;
use Livewire\Component;

class IncomeStatementMonthly extends Component
{

    public $dataList = [];

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
        $YEAR = (int) $result['YEAR'];
        $LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->dataList = [];
        $accountList = $this->accountServices->IncomeStatementMonthly();
        $TMP = 0;
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
            $data = $this->financialStatementServices->getIncomeStatementByMonth($list->ID, $YEAR, $LOCATION_ID);
            if ($data) {
                $JAN = $data->JAN ?? 0;
                $FEB = $data->FEB ?? 0;
                $MAR = $data->MAR ?? 0;
                $APR = $data->APR ?? 0;
                $MAY = $data->MAY ?? 0;
                $JUN = $data->JUN ?? 0;
                $JUL = $data->JUL ?? 0;
                $AUG = $data->AUG ?? 0;
                $SEP = $data->SEP ?? 0;
                $OCT = $data->OCT ?? 0;
                $NOV = $data->NOV ?? 0;
                $DEC = $data->DEC ?? 0;
                $TOTAL = $data->TOTAL ?? 0;
            }


            if ($TMP != $list->TYPE) {
                if ($TMP == 0) {
                    $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE($list->TYPE), '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                } else {



                    $this->dataList[] = $this->getInsert($list->ID, 'Total ' . $this->customTYPE($TMP), '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                    $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    if ($TMP == 13 && $list->TYPE == 12) {
                        $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE(21), '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                        $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    }

                    $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE($list->TYPE), '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                }


            }
            $TMP = $list->TYPE;
            $this->dataList[] = $this->getInsert(
                $list->ID,
                $list->NAME,
                $list->TYPE,
                number_format($JAN, 2),
                number_format($FEB, 2),
                number_format($MAR, 2),
                number_format($APR, 2),
                number_format($MAY, 2),
                number_format($JUN, 2),
                number_format($JUL, 2),
                number_format($AUG, 2),
                number_format($SEP, 2),
                number_format($OCT, 2),
                number_format($NOV, 2),
                number_format($DEC, 2),
                number_format($TOTAL, 2)
            );
        }
        $this->dataList[] = $this->getInsert($list->ID, 'Total ' . $this->customTYPE($TMP), '', '', '', '', '', '', '', '', '', '', '', '', '', '');

        $this->dataList[] = $this->getInsert(0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

        $this->dataList[] = $this->getInsert(0, $this->customTYPE(22), '', '', '', '', '', '', '', '', '', '', '', '', '', '');

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
    public function render()
    {
        return view('livewire.income-statement.income-statement-monthly');
    }
}
