<?php

namespace App\Livewire\FinancialReport;

use App\Exports\BalanceSheetExport;
use App\Services\DateServices;
use App\Services\FinancialStatementServices;
use App\Services\LocationServices;
use App\Services\NumberServices;
use App\Services\UserServices;
use DateTime;
use Livewire\Attributes\Title;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Title('Balance Sheet Report')]
class BalanceSheetReport extends Component
{


    public string $DATE_FROM;
    public string $DATE_TO;
    public int $LOCATION_ID;
    public $locationList = [];
    public $dataList = [];


    private $financialStatementServices;
    private $locationServices;
    private $userServices;
    private $dateServices;
    private $numberServices;
    public function boot(
        FinancialStatementServices $financialStatementServices,
        LocationServices $locationServices,
        UserServices $userServices,
        DateServices $dateServices,
        NumberServices $numberServices
    ) {
        $this->financialStatementServices = $financialStatementServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
        $this->numberServices = $numberServices;
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

        $this->dataList = [];
        $this->HeaderParameter('HEADER', 'HEADER', 'ASSETS');
        // Assets
        $bankList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [0], false);
        $bankTotal =  $this->SetParameter($bankList);

        $arList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [1], false);
        $arTotal = $this->SetParameter($arList);

        $currentAssetList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [2], false);
        $currentAssetTotal  = $this->SetParameter($currentAssetList);

        $fixedAssetList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [3], false);
        $fixAssetTotal = $this->SetParameter($fixedAssetList);

        $nonCurrentAssetList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [4], false);
        $nonCurrentAssetTotal =  $this->SetParameter($nonCurrentAssetList);

        $totalAsset = $bankTotal +  $arTotal +  $currentAssetTotal + $fixAssetTotal +  $nonCurrentAssetTotal;

        $this->TotalParameter('HEADER', 'HEADER', 'TOTAL ASSETS', $totalAsset);


        $this->HeaderParameter('HEADER', 'HEADER', 'LIABILITIES');
        // Liabilities
        $apList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [5], true);
        $apTotal =  $this->SetParameter($apList);

        $creditCardList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [6], true);
        $creditCardTotal =  $this->SetParameter($creditCardList);

        $currentLiabilityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [7], true);
        $currentLiabilityTotal = $this->SetParameter($currentLiabilityList);

        $nonCurrentLiabilityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [8], true);
        $nonCurrentLiabilityTotal =  $this->SetParameter($nonCurrentLiabilityList);
        $totalLiabilities =  $apTotal  +  $creditCardTotal +  $currentLiabilityTotal +  $nonCurrentLiabilityTotal;

        $this->TotalParameter('HEADER', 'HEADER', 'TOTAL LIABILITIES', $totalLiabilities);



        $this->dataList[] = [
            'ORDER'     => '',
            'TYPE'      => '',
            'ACCOUNT'   => '',
            'AMOUNT'    => '__________________________'
        ];

        $this->TotalParameter('N', 'NET ASSETS', 'NET ASSETS',  $totalAsset - $totalLiabilities);


        $this->dataList[] = [
            'ORDER'     => '',
            'TYPE'      => '',
            'ACCOUNT'   => '',
            'AMOUNT'    => ' '
        ];
        $this->HeaderParameter('HEADER', 'HEADER', 'EQUITY');
        // Equity
        $equityList = $this->financialStatementServices->getBalanceSheetAccountByAcctType($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID, [9], true);
        $equityTotal =  $this->SetParameter($equityList);

        $net_income = $this->financialStatementServices->getTotalNetIncome($this->DATE_FROM, $this->DATE_TO, $this->LOCATION_ID);
        $this->CustomParameter('D', '', 'Net Income', $net_income);

        $RetainingEarnings = $this->HistoryRetainingEarnings($this->DATE_FROM);
        $this->CustomParameter('D', '', 'Retaining Earnings', $RetainingEarnings);
        $newEquty = $RetainingEarnings + $net_income + $equityTotal;
        $this->TotalParameter('HEADER', 'HEADER', 'TOTAL EQUITY', $newEquty );

        $this->dataList[] = [
            'ORDER'     => '',
            'TYPE'      => '',
            'ACCOUNT'   => '',
            'AMOUNT'    => '__________________________'
        ];
        $TOTAL = $totalLiabilities + $newEquty;

        $this->TotalParameter('S', '', 'TOTAL LIABILITIES & EQUITY', $TOTAL);
    }
    public function HistoryRetainingEarnings($PREV_DATE): float
    {
        $START_BUSSINES_DATE = '2020-1-1';
        $NEW_DATE = date('Y-m-d', strtotime($PREV_DATE . ' -1 day'));
        $amt = $this->financialStatementServices->getTotalNetIncome($START_BUSSINES_DATE, $NEW_DATE, $this->LOCATION_ID);

        return $amt;
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
    private function SetParameter($DataList): float
    {
        $haveData = false;
        $TYPE = '';
        $PRE_AMOUNT = 0.00;
        foreach ($DataList as $list) {

            $haveData = true;
            if ($TYPE == '') {
                $TYPE = $list->ACCOUNT_TYPE;
                // HEADER
                $this->dataList[] = [
                    'ORDER'     => $list->ORDER,
                    'TYPE'      => $list->ACCOUNT_TYPE,
                    'ACCOUNT'   => $list->ACCOUNT_TYPE,
                    'AMOUNT'    => ''
                ];

                // Data
                $this->dataList[] = [
                    'ORDER'     => $list->ORDER,
                    'TYPE'      => $list->ACCOUNT_TYPE,
                    'ACCOUNT'   => $list->ACCOUNT_TITLE,
                    'AMOUNT'    => $this->numberServices->Fixed($list->AMOUNT)
                ];
            } else {
                // Data
                $this->dataList[] = [
                    'ORDER'     => $list->ORDER,
                    'TYPE'      => $list->ACCOUNT_TYPE,
                    'ACCOUNT'   => $list->ACCOUNT_TITLE,
                    'AMOUNT'    => $this->numberServices->Fixed($list->AMOUNT)
                ];
            }

            $PRE_AMOUNT =  $PRE_AMOUNT +  $list->AMOUNT ?? 0.00;
        }

        if ($haveData) {

            // TOTAL

            $this->dataList[] = [
                'ORDER'     => '',
                'TYPE'      => ' ',
                'ACCOUNT'   => '         ',
                'AMOUNT'    => '__________________________'
            ];

            $this->dataList[] = [
                'ORDER'     => '',
                'TYPE'      => "Total $TYPE",
                'ACCOUNT'   => "Total $TYPE",
                'AMOUNT'    =>  $this->numberServices->Fixed($PRE_AMOUNT)
            ];
        }
        return $PRE_AMOUNT ?? 0.00;
    }
    private function CustomParameter(string $order, string $type, string $Account, float $Amount)
    {
        if ($Amount > 0) {

            $this->dataList[] = [
                'ORDER'     => $order,
                'TYPE'      => $type,
                'ACCOUNT'   => $Account,
                'AMOUNT'    => $this->numberServices->Fixed($Amount)
            ];
        }
    }
    private function HeaderParameter(string $order, string $type, string $Account)
    {
        $this->dataList[] = [
            'ORDER'     => $order,
            'TYPE'      => $type,
            'ACCOUNT'   => $Account,
            'AMOUNT'    => ''
        ];
    }
    private function TotalParameter(string $order, string $type, string $Account, float $Amount)
    {


        $this->dataList[] = [
            'ORDER'     => $order,
            'TYPE'      => $type,
            'ACCOUNT'   => $Account,
            'AMOUNT'    => $this->numberServices->Fixed($Amount)
        ];
    }

    public function export()
    {
        if (!$this->dataList) {
            session()->flash('error', 'Please generate first.');
            return;
        }
        return Excel::download(new BalanceSheetExport(
            $this->dataList
        ), 'balance-sheet-export.xlsx');
    }
    public function render()
    {
        return view('livewire.financial-report.balance-sheet-report');
    }
}
