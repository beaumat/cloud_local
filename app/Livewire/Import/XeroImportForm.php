<?php

namespace App\Livewire\Import;

use App\Imports\ExcelDataImport;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\XeroDataServices;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;


#[Title('Import Xero - Transaction Account')]
class XeroImportForm extends Component
{


    use WithFileUploads;
    public $file = null;

    public $dataList = [];
    public $locationList = [];
    public $year;
    public $month;

    public $yearList = [];
    public $monthList = [];

    public $locationid = 0;
    private $locationServices;
    private $xeroDataServices;
    private $dateServices;
    public function boot(LocationServices $locationServices, XeroDataServices $xeroDataServices, DateServices $dateServices)
    {
        $this->locationServices = $locationServices;
        $this->xeroDataServices = $xeroDataServices;
        $this->dateServices = $dateServices;
    }

    public function generate()
    {

        $this->validate([
            'locationid' => 'required|exists:location,id'
        ], [], [
            'locationid' => 'Location'
        ]);


        $this->dataList = $this->xeroDataServices->viewData($this->locationid, $this->year, $this->month);

    }
    public function makeEntry()
    {

    }
    public function GetReferenceEntry(string $REF) {
        
    }
    public function mount()
    {
        $this->locationList = $this->locationServices->getList();
        $this->yearList = $this->dateServices->YearList();
        $this->monthList = $this->dateServices->MonthList();

        $this->year = $this->dateServices->NowYear();
        $this->month = $this->dateServices->NowMonth();
    }
    public function render()
    {
        return view('livewire.import.xero-import-form');
    }
}
