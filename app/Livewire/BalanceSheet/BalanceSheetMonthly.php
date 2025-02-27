<?php

namespace App\Livewire\BalanceSheet;

use App\Services\AccountServices;
use App\Services\FinancialStatementServices;
use Livewire\Attributes\On;
use Livewire\Component;

class BalanceSheetMonthly extends Component
{
    public $YEAR;
    public $LOCATION_ID;
    public $dataList = [];
    private $financialStatementServices;
    private $accountServices;
    public function boot(FinancialStatementServices $financialStatementServices, AccountServices $accountServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->accountServices = $accountServices;
    }
    #[On('balance-sheet-monthly')]
    public function generate($result)
    {
        $this->dataList = [];
        $this->YEAR = $result['YEAR'];
        $this->LOCATION_ID = $result['LOCATION_ID'];
        $acctList = $this->accountServices->BalanceSheetMonthly();

        $type = -1;

        foreach ($acctList as $list) {


            if ($type == -1) {
                $this->dataList[] = $this->getInsert($list->ID, 'ASSET', 'grand', '', '', '', '', '', '', '', '', '', '', '', '', '');
            }
            if ($type <> $list->TYPE) {
                if ($type >= 0) {
                    $this->dataList[] = $this->getInsert($list->ID, 'Total ' . $this->customTYPE($type), 'total', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');




                }

                if ($type <> $list->TYPE && $list->TYPE >= 5 && $type <= 4 && $list->TYPE <> 9) {
                    $this->dataList[] = $this->getInsert($list->ID, 'TOTAL ASSET', 'grand', '', '', '', '', '', '', '', '', '', '', '', '', '');
                    $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    $this->dataList[] = $this->getInsert($list->ID, 'LIABILITIES', 'grand', '', '', '', '', '', '', '', '', '', '', '', '', '');

                }

                if ($list->TYPE == 9) {
                    $this->dataList[] = $this->getInsert($list->ID, 'TOTAL LIABILITIES', 'grand', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    $this->dataList[] = $this->getInsert($list->ID, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

                    $this->dataList[] = $this->getInsert($list->ID, 'EQUITY', 'grand', '', '', '', '', '', '', '', '', '', '', '', '', '');
                }

                $this->dataList[] = $this->getInsert($list->ID, $this->customTYPE($list->TYPE), 'total', '', '', '', '', '', '', '', '', '', '', '', '', '');

            }




            $this->dataList[] = $this->getInsert($list->ID, $list->NAME, $list->TYPE, '', '', '', '', '', '', '', '', '', '', '', '', '');
            $type = $list->TYPE;

        }

    }
    private function customTYPE(int $ID)
    {
        switch ($ID) {
            case 0:
                return 'Bank';

            case 1:
                return 'Accounts Receivable';

            case 2:
                return 'Current Asset';

            case 3:
                return 'Fixed Asset';

            case 4:
                return 'Non-Current Asset';

            case 5:
                return 'Accounts Payable';
            case 6:
                return 'Credit Card';
            case 7:
                return 'Current Liability';
            case 8:
                return 'Non-Current Liability';
            case 9:
                return 'Equity';
        }
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
        return view('livewire.balance-sheet.balance-sheet-monthly');
    }
}
