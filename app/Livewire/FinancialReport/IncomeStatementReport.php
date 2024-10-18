<?php

namespace App\Livewire\FinancialReport;

use App\Services\AccountJournalServices;
use App\Services\AccountServices;
use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Income Statement Report')]
class IncomeStatementReport extends Component
{


    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public $incomeList =  [];
    public $cogsList = [];

    public $expensesList = [];
    public $otherIncomeList = [];
    public $otherExpensesList = [];

    public float $amount = 0;
    public float $sub_total =  0;
    public float $total_income = 0;
    public float $total_cogs = 0;
    public float $gross_profit = 0;
    public float $total_expenses = 0;
    public float $total_other_income = 0;
    public float $total_other_expenses = 0;

    public float $operating_income = 0;
    public float $net_other_income = 0;
    public float $net_income = 0;

    private $financialStatementServices;
    private $dateServices;
    private $locationServices;
    private $userServices;

    public function boot(
        FinancialStatementServices $financialStatementServices,
        DateServices $dateServices,
        LocationServices $locationServices,
        UserServices $userServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->dateServices = $dateServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->DATE_FROM = $this->userServices->getTransactionDateDefault();
        $this->DATE_TO = $this->userServices->getTransactionDateDefault();
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate()
    {
        $this->incomeList = $this->financialStatementServices->IncomeAccount(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID
        );


        $this->cogsList = $this->financialStatementServices->CogsAccount(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID
        );


        $this->expensesList = $this->financialStatementServices->ExpensesAccount(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID
        );

        $this->otherIncomeList = $this->financialStatementServices->OtherIncomeAccount(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID
        );

        $this->otherExpensesList = $this->financialStatementServices->OtherExpensesAccount(
            $this->DATE_FROM,
            $this->DATE_TO,
            $this->LOCATION_ID
        );
    }
    public function render()
    {
        return view('livewire.financial-report.income-statement-report');
    }
}
