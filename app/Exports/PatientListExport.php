<?php

namespace App\Exports;

use App\Services\ContactServices;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PatientListExport implements FromCollection, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $contactServices;
    public int $doctorid;
    protected int $locationId;
    protected $search;
    protected string $sortBy;
    protected bool $isDesc;
    public function __construct(ContactServices $contactServices, int $doctorid, int $locationId, string $search, string $sortBy, bool $isDesc)
    {
        $this->contactServices = $contactServices;
        $this->doctorid = $doctorid;
        $this->locationId = $locationId;
        $this->search = $search;
        $this->sortBy = $sortBy;
        $this->isDesc = $isDesc;
    }

    public function collection()
    {
        $dataList = $this->contactServices->SearchPatient($this->search, 1000, $this->locationId, $this->sortBy, $this->isDesc, $this->doctorid);
        $headers = [
            'NO'            => 'No.',
            'LN'            => 'Lastname',
            'FN'            => 'Firstname',
            'MN'            => 'Middlename',
            'SEX'           => 'Sex',
            'DOB'           => 'Date of Birth',
            'AGE'           => 'Age',
            'PHILHEALTH'    => 'Philhealth No.',
            'DOCTOR'        => 'Nepro/Doctor',
            'DIAGNOSIS'     => 'Diagnosis On',
            'Location'      => 'Location',
            'REQ'           => 'Req. Status',
            'INACTIVE'      => 'Inactive'
        ];
        $finalData[] = array_values($headers);
        foreach ($dataList as $list) {
            $rowData = [
                'NO'            =>  $list->ACCOUNT_NO,
                'LN'            =>  $list->LAST_NAME,
                'FN'            =>  $list->FIRST_NAME,
                'MN'            =>  $list->MIDDLE_NAME,
                'SEX'           =>  $list->GENDER,
                'DOB'           =>  $list->DATE_OF_BIRTH,
                'AGE'           =>  $list->AGE,
                'PHILHEALTH'    =>  " $list->PIN",
                'DOCTOR'        =>  $list->DOCTOR_NAME,
                'DIAGNOSIS'     =>  $list->DATE_ADMISSION,
                'Location'      =>  $list->LOCATION_NAME,
                'REQ'           =>  $list->IS_COMPLETE ? 'Yes' : 'No',
                'INACTIVE'      =>  $list->INACTIVE ? 'Yes' : 'No'
            ];


            $finalData[] = array_values($rowData);
        }

        return collect($finalData);
    }
}
