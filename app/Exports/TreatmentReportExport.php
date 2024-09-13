<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class TreatmentReportExport implements FromCollection
{   
    public int $YEAR;
    public int $MONTH;
    public $dataList = [];
    public array $dailyList = [];
    public int $count = 0;
    public int $index = 0;
    public int $row = 0;
    public int $total;
    public int $sum = 0;
    public $startDate;
    public $endDate;
    public int $patient = 0;
    public $storeTotal = [];
    public $phicTotal = [];
    public $premTotal = [];

    public function __construct() {
        
    }

    public function DaySetup()
    {
        $this->dailyList = [];
        $this->storeTotal = [];
        $this->phicTotal = [];
        $this->premTotal =  [];

        $this->startDate = Carbon::create($this->YEAR,  $this->MONTH, 1); // August 1st of the current year
        $this->endDate = $this->startDate->copy()->endOfMonth(); // End of August

        // Loop through each day in August
        for ($date = $this->startDate; $date->lte($this->endDate); $date->addDay()) {
            $this->dailyList[] = $date->format('Y-m-d'); // Format the date as 'Y-m-d'
            $this->storeTotal[] = 0;
            $this->phicTotal[]  = 0;
            $this->premTotal[] = 0;
        }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {       
        $this->DaySetup();

        //
    }



}
