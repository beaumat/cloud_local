<?php

namespace App\Livewire\FinancialReport;

use App\Exports\IncomeStatementExport;
use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\NumberServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Income Statement Report')]
class IncomeStatementReport extends Component
{

    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList = [];


    private $financialStatementServices;
    private $dateServices;
    private $locationServices;
    private $userServices;
    private $numberServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices,
        NumberServices $numberServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->numberServices = $numberServices;
    }
    public function mount()
    {

        $this->DATE_TO = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Month($this->DATE_TO);
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate()
    {
        $this->dataList = [];

        $incomeList = $this->financialStatementServices->IncomeAccount($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $totalIncome =  $this->SetParameter($incomeList, 'REVENUE');
        $this->SetTotal('TOTAL REVENUE', $totalIncome);

        $cogsList = $this->financialStatementServices->CogsAccount($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $totalCogs =  $this->SetParameter($cogsList, 'COGS');
        $this->SetTotal('TOTAL COGS', $totalCogs);

        $totalGross = $totalIncome -  $totalCogs;
        $this->SetPrimaryTotal('GROSS PROFIT', $totalGross);

        $expensesList = $this->financialStatementServices->ExpensesAccount($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $totalExpenses =  $this->SetParameter($expensesList, 'EXPENSES');
        $this->SetTotal('TOTAL EXPENSES', $totalExpenses);

        $opIncome = $totalGross - $totalExpenses;
        $this->SetPrimaryTotal('OPERATING INCOME', $opIncome);

        $otherIncomeList = $this->financialStatementServices->OtherIncomeAccount($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $totalOtherIncome =  $this->SetParameter($otherIncomeList, 'OTHER INCOME');
        $this->SetTotal('TOTAL OTHER INCOME', $totalOtherIncome);

        $otherExpensesList = $this->financialStatementServices->OtherExpensesAccount($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $totalOtherExpenses =  $this->SetParameter($otherExpensesList,  'OTHER EXPENSES');
        $this->SetTotal('TOTAL OTHER EXPENSES', $totalOtherExpenses);
        
        $net_other_income = $totalOtherIncome - $totalOtherExpenses;

        $this->SetPrimaryTotal('NET OTHER INCOME', $net_other_income);

        $netIncome =  $opIncome  + $net_other_income;

        $this->SetTotal('NET INCOME', $netIncome);
    }

    public function updatedlocationid()
    {
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    private function SetParameter($dataList = [], string $TYPE): float
    {
        $TYPE_TITLE = '';
        $total = 0;
        foreach ($dataList as $list) {
            if ($TYPE_TITLE == '') {
                $TYPE_TITLE = $TYPE;
                $this->dataList[] = [
                    'TYPE'      => 'H',
                    'ACCOUNT'   => $TYPE_TITLE,
                    'AMOUNT'    => '',
                    'TOTAL'     => ''
                ];
            }

            $this->dataList[] = [
                'TYPE'      => $TYPE,
                'ACCOUNT'   => $list->ACCOUNT_TITLE,
                'AMOUNT'    => $this->numberServices->Fixed($list->AMOUNT),
                'TOTAL'     => ''
            ];

            $total =  $total + $list->AMOUNT ?? 0;
        }

        return $total;
    }
    private function SetTotal(string $TITLE, float $TOTAL)
    {
        if ($TOTAL == 0) {
            return;
        }


        $this->dataList[] = [
            'TYPE'      => '',
            'ACCOUNT'   => '',
            'AMOUNT'    => '',
            'TOTAL'     => ''
        ];
        $this->dataList[] = [
            'TYPE'      => 'H',
            'ACCOUNT'   => $TITLE,
            'AMOUNT'    => '',
            'TOTAL'     => $this->numberServices->Fixed($TOTAL)
        ];
    }

    private function SetPrimaryTotal(string $TITLE, float $TOTAL)
    {
        if ($TOTAL == 0) {
            return;
        }


        $this->dataList[] = [
            'TYPE'      => '',
            'ACCOUNT'   => '',
            'AMOUNT'    => '',
            'TOTAL'     => ''
        ];
        $this->dataList[] = [
            'TYPE'      => 'P',
            'ACCOUNT'   => $TITLE,
            'AMOUNT'    => '',
            'TOTAL'     => $this->numberServices->Fixed($TOTAL)
        ];
    }
    public function export()
    {
        if (!$this->dataList) {
            session()->flash('error', 'Please generate first.');
            return;
        }
        return Excel::download(new IncomeStatementExport(
            $this->dataList
        ), 'income-statement-export.xlsx');
    }
    public function render()
    {
        return view('livewire.financial-report.income-statement-report');
    }
}
