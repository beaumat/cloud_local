<?php

namespace App\Livewire\FinancialReport;

use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use DateTime;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Balance Sheet Report')]
class BalanceSheetReport extends Component
{


    public string $DATE_FROM;
    public string $DATE_TO;

    public int $LOCATION_ID;
    public $locationList = [];

    public float $amount = 0;
    public float $bankTotal = 0;
    public float $arTotal = 0;
    public float $currentAssetTotal = 0;
    public float $fixedAssetTotal = 0;
    public float $nonCurrentAssietTotal = 0;
    public $assetTotal = 0;

    public $bankList = [];
    public $arList = [];
    public $currentAssetList = [];
    public $fixedAssetList = [];
    public $nonCurrentAssietList = [];
    public float $apTotal = 0;
    public float $creditCardTotal = 0;
    public float $currentLiabilityTotal = 0;
    public float $nonCurrentLiabilityTotal = 0;
    public float $liabilityTotal = 0;
    public $apList = [];
    public $creditCardList = [];
    public $currentLiabilityList = [];
    public $nonCurrentLiabilityList = [];

    public float $netAsset = 0;
    public float $equityTotal = 0;
    public $equityList = [];
    public float $CurrentYearEarnings = 0;
    public float $RetainingEarnings = 0;
    public float $dividend = 0;
    public float $ownersequity = 0;
    public float $net_income = 0;
    public float $history_net_income = 0;
    public float $history_retaining_earnings = 0;
    private $financialStatementServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function mount()
    {
        $this->DATE_TO = $this->userServices->getTransactionDateDefault();
        $this->DATE_FROM = $this->dateServices->GetFirstDay_Year($this->DATE_TO);
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->locationList = $this->locationServices->getList();
    }
    public function generate()
    {

        $this->netAsset = 0;
        $this->assetTotal = 0;
        $this->bankTotal = 0;
        $this->arTotal = 0;
        $this->currentAssetTotal = 0;
        $this->fixedAssetTotal = 0;
        $this->nonCurrentAssietTotal = 0;

        $this->bankList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [0], false);
        $this->arList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [1], false);
        $this->currentAssetList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [2], false);
        $this->fixedAssetList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [3], false);
        $this->nonCurrentAssietList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [4], false);

        $this->liabilityTotal = 0;
        $this->apTotal = 0;
        $this->creditCardTotal = 0;
        $this->currentLiabilityTotal = 0;
        $this->nonCurrentLiabilityTotal = 0;

        $this->apList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [5], true);
        $this->creditCardList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [6], true);
        $this->currentLiabilityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [7], true);
        $this->nonCurrentLiabilityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [8], true);
        $this->equityTotal = 0;
        $this->equityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [9], true);

        $this->CurrentYearEarnings = 0;
        $this->net_income = $this->financialStatementServices->getTotalNetIncome($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $this->RetainingEarnings = $this->HistoryRetainingEarnings($this->DATE_FROM);
    }
    public function HistoryRetainingEarnings($PREV_DATE): float
    {
        $START_BUSSINES_DATE = '2020-1-1';
        $NEW_DATE = date('Y-m-d', strtotime($PREV_DATE . ' -1 day'));
        $amt = $this->financialStatementServices->getTotalNetIncome($START_BUSSINES_DATE, $NEW_DATE, $this->LOCATION_ID);
        return $amt;
    }
    public function render()
    {
        return view('livewire.financial-report.balance-sheet-report');
    }
    public function updatedlocationid()
    {   
        try {
            $this->userServices->SwapLocation($this->LOCATION_ID );
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
 
    }
}
