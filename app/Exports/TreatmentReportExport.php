<?php

namespace App\Exports;

use App\Services\PatientReportServices;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;

class TreatmentReportExport implements FromCollection
{
    protected int $YEAR;
    protected int $MONTH;
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

    protected $patientSelected = [];
    protected $LOCATION_ID;
    protected $patientReportServices;

    public function __construct(PatientReportServices $patientReportServices, int $YEAR, int $MONTH, array $patientSelected, int $LOCATION_ID)
    {
        $this->patientReportServices = $patientReportServices;
        $this->YEAR = $YEAR;
        $this->MONTH = $MONTH;
        $this->patientSelected = $patientSelected;
        $this->LOCATION_ID = $LOCATION_ID;
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

    public function generateData()
    {
        $this->dataList = $this->patientReportServices->getMonthlyTreatment(
            $this->YEAR,
            $this->MONTH,
            $this->dailyList,
            $this->patientSelected,
            $this->LOCATION_ID
        );
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->DaySetup();
        $this->generateData();

    //     <tr>
    //     <th class="text-center ">No.</th>
    //     <th class="col-3">Patient Name</th>
    //     @foreach ($dailyList as $day)
    //         <th class="text-center">
    //             {{ date('d', strtotime($day)) }}<br />{{ date('D', strtotime($day)) }}</th>
    //     @endforeach
    //     <th class="text-center">Total</th>
    // </tr>
    //     $daySet = [];
    //     foreach($dailyList as $day) {
    //         $daySet[] = date('d', strtotime($day)) . ' ' . date('D', strtotime($day));
    //     }
        $headers = [
            '#'     =>  '#',
            'NO'    => 'NO',
            'NAME'  => 'NAME',
    
            'TOTAL'         => 'TOTAL'
        ];

        $NUMBER = 0;
        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row












        return collect($finalData);

    }
}
