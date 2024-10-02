<?php

namespace App\Exports;

use App\Services\PatientReportServices;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TreatmentReportExport implements FromCollection, ShouldAutoSize
{
    protected int $YEAR;
    protected int $MONTH;
    public $dataList = [];
    public $dailyList = [];
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


        $headers = [
            'NO'    => 'NO',
            'NAME'  => 'NAME',
        ];
        $dayIndex = 0;
        foreach ($this->dailyList as $day) {
            $strDay = date('d', strtotime($day)) . ' ' . date('D', strtotime($day));

            $headers[] = $strDay;
            $dayIndex = $dayIndex  + 1;
        }
        $headers[] = 'TOTAL';

        $finalData = [];
        $finalData[] = array_values($headers); // Add headers as the first row

        $patient = 0;
        $total = 0;


        foreach ($this->dataList as $list) {
            $row = [];
            $count = 0;
            $index = 0;
            $patient = $patient + 1;
            $row[] = $patient;
            $row[] = $list->PATIENT_NAME;

            foreach ($this->dailyList as $day) {
                if ($list[date('d', strtotime($day))] == 1) {
                    $this->phicTotal[$index] = $this->phicTotal[$index] + 1;
                }
                if ($list[date('d', strtotime($day))] == 2) {
                    $this->premTotal[$index] = $this->premTotal[$index] + 1;
                }
                if ($list[date('d', strtotime($day))]) {

                    if($list[date('d', strtotime($day))] == 1){
                        $row[] = 'G';
                    }elseif($list[date('d', strtotime($day))] == 2){
                        $row[] = 'P';
                    }
                    else{
                        $row[] = '';
                    }
                  
                    $this->storeTotal[$index] = $this->storeTotal[$index] + 1;
                    $count++;
                }
                else {
                    $row[] = '';
                    
                }
                $index++;
            }

            $row[] = $count;
            $total = $total + $count;

            $finalData[] = array_values($row);
        }

        $row = [];
        $finalData[] = array_values($row);
        $index = 0;
        $sum = 0;

        $row = [];
        $row[] = '';
        $row[] = 'No. of Treatment W/ PHIC';


        foreach ($this->dailyList as $day) {
            $row[] = $this->phicTotal[$index];
            $sum = $sum + $this->phicTotal[$index];
            $index++;
        }
        $row[] = $sum;
        $finalData[] = array_values($row);
        $row = [];

        $index = 0;
        $sum = 0;
        $row[] = '';
        $row[] = 'No. of Treatment Priming';

        foreach ($this->dailyList as $day) {
            $row[] = $this->premTotal[$index];
            $sum = $sum + $this->premTotal[$index];
            $index++;
        }
        $row[] = $sum;
        $finalData[] = array_values($row);
        $row = [];

        $index = 0;
        $row[] = '';
        $row[] = 'No. of Treatment';

        foreach ($this->dailyList as $day) {
            $row[] = $this->storeTotal[$index];
            $index++;
        }
        $row[] = $total;
        $finalData[] = array_values($row);

        return collect($finalData);
    }
}
