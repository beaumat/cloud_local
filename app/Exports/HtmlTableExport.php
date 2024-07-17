<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class HtmlTableExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {


        return view('livewire.patient-report.patient-sales-report', [
            'PATIENT_ID' => 0,
            'patientList' => [],
            'locationList' => [],
            'dataList' => [],
            'NO_OF_PATIENT' => 0,
            'TOTAL_CHARGE' => 0,
            'TOTAL_PAID' => 0,
        ]); // Replace with your view name
    }
}
