<?php

namespace App\Livewire\IncomeStatement;

use App\Services\FinancialStatementServices;
use App\Services\NumberServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class IncomeStatementDateRange extends Component
{

    
    public $dataList = [];


    private $financialStatementServices;
    private $numberServices;
    public function boot(FinancialStatementServices $financialStatementServices,NumberServices $numberServices)
    {
        $this->financialStatementServices = $financialStatementServices;
        $this->numberServices = $numberServices;
    }
    #[On('income-date-range')]
    public function generate($result)
    {
        $this->dataList = [];
        $DATE_FROM = $result['DATE_FROM'];
        $DATE_TO = $result['DATE_TO'];
        $LOCATION_ID = $result['LOCATION_ID'];

        $incomeList = $this->financialStatementServices->IncomeAccount($DATE_FROM, $DATE_TO, $LOCATION_ID);
        $totalIncome = $this->SetParameter($incomeList, 'REVENUE');
        $this->SetTotal('TOTAL REVENUE', $totalIncome);

        $cogsList = $this->financialStatementServices->CogsAccount($DATE_FROM, $DATE_TO, $LOCATION_ID);
        $totalCogs = $this->SetParameter($cogsList, 'COGS');
        $this->SetTotal('TOTAL COGS', $totalCogs);

        $totalGross = $totalIncome - $totalCogs;
        $this->SetPrimaryTotal('GROSS PROFIT', $totalGross);

        $expensesList = $this->financialStatementServices->ExpensesAccount($DATE_FROM, $DATE_TO, $LOCATION_ID);
        $totalExpenses = $this->SetParameter($expensesList, 'EXPENSES');
        $this->SetTotal('TOTAL EXPENSES', $totalExpenses);

        $opIncome = $totalGross - $totalExpenses;
        $this->SetPrimaryTotal('OPERATING INCOME', $opIncome);

        $otherIncomeList = $this->financialStatementServices->OtherIncomeAccount($DATE_FROM, $DATE_TO, $LOCATION_ID);
        $totalOtherIncome = $this->SetParameter($otherIncomeList, 'OTHER INCOME');
        $this->SetTotal('TOTAL OTHER INCOME', $totalOtherIncome);

        $otherExpensesList = $this->financialStatementServices->OtherExpensesAccount($DATE_FROM, $DATE_TO, $LOCATION_ID);
        $totalOtherExpenses = $this->SetParameter($otherExpensesList, 'OTHER EXPENSES');
        $this->SetTotal('TOTAL OTHER EXPENSES', $totalOtherExpenses);

        $net_other_income = $totalOtherIncome - $totalOtherExpenses;
        $this->SetPrimaryTotal('NET OTHER INCOME', $net_other_income);

        $netIncome = $opIncome + $net_other_income;
        $this->SetTotal('NET INCOME', $netIncome);
    }
    private function SetParameter($dataList = [], string $TYPE): float
    {
        $TYPE_TITLE = '';

        $total = 0;

        foreach ($dataList as $list) {
            if ($TYPE_TITLE == '') {
                $TYPE_TITLE = $TYPE;
                $this->dataList[] = [
                    'TYPE' => 'H',
                    'ACCOUNT' => $TYPE_TITLE,
                    'AMOUNT' => '',
                    'TOTAL' => ''
                ];
            }

            $this->dataList[] = [
                'TYPE' => $TYPE,
                'ACCOUNT' => $list->ACCOUNT_TITLE,
                'AMOUNT' => $this->numberServices->Fixed($list->AMOUNT),
                'TOTAL' => ''
            ];

            $total = $total + $list->AMOUNT ?? 0;
        }

        return $total;
    }
    private function SetTotal(string $TITLE, float $TOTAL)
    {
        if ($TOTAL == 0) {
            return;
        }


        $this->dataList[] = [
            'TYPE' => '',
            'ACCOUNT' => '',
            'AMOUNT' => '',
            'TOTAL' => ''
        ];
        $this->dataList[] = [
            'TYPE' => 'H',
            'ACCOUNT' => $TITLE,
            'AMOUNT' => '',
            'TOTAL' => $this->numberServices->Fixed($TOTAL)
        ];
    }

    private function SetPrimaryTotal(string $TITLE, float $TOTAL)
    {
        if ($TOTAL == 0) {
            return;
        }


        $this->dataList[] = [
            'TYPE' => '',
            'ACCOUNT' => '',
            'AMOUNT' => '',
            'TOTAL' => ''
        ];
        $this->dataList[] = [
            'TYPE' => 'P',
            'ACCOUNT' => $TITLE,
            'AMOUNT' => '',
            'TOTAL' => $this->numberServices->Fixed($TOTAL)
        ];
    }
    public function render()
    {
        return view('livewire.income-statement.income-statement-date-range');
    }
}
