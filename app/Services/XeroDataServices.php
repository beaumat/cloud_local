<?php

namespace App\Services;

use App\Models\XeroData;

class XeroDataServices
{

    public function __construct()
    {

    }



    public function viewData(int $locationId, int $year = 0, int $month = 0)
    {

        $result = XeroData::where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->when($year > 0, function ($query) use (&$year) {
                $query->whereYear('DATE', $year);
            })
            ->when($month > 0, function ($query) use (&$month) {
                $query->whereMonth('DATE', $month);
            })
            ->get();

        return $result;

    }
    public function viewDataPerGroupReference(int $locationId, int $year = 0, int $month = 0)
    {
        $result = XeroData::where('LOCATION_ID', '=', $locationId)
            ->where('POSTED', '=', 0)
            ->when($year > 0, function ($query) use (&$year) {
                $query->whereYear('DATE', $year);
            })
            ->when($month > 0, function ($query) use (&$month) {
                $query->whereMonth('DATE', $month);
            })
            ->groupBy(['REFERENCE', 'DATE', 'SOURCE_TYPE'])
            ->get();

        return $result;
    }
    public function callReference(string $ref)
    {
        $result = XeroData::query()
            ->where('REFERENCE', '=', $ref)
            ->where('POSTED', '=', 0)
            ->orderBy('DATE')
            ->orderBy('SOURCE_TYPE')
            ->orderBy('REFERENCE')
            ->get();

        return $result;
    }
    public function DocumentType(string $SOURCE_TYPE): int
    {
        $docType = 0;
        switch ($SOURCE_TYPE) {
            case 'Payable Invoice':
                $docType = 1; //bill
                break;
            case 'Payable Payment':
                $docType = 2; // bill payment
                break;
            case 'Receivable Invoice':
                $docType = 10; // invoice
                break;
            case 'Receivable Payment':
                $docType = 11; //payment;
                break;
            case 'Manual Journal':
                $docType = 23; //General Journal;
                break;
            case 'Bank Transfer':
                $docType = 26; //Fund Transfer;
                break;
            case 'Receive Money':
                $docType = 26; //Fund Transfer;
                break;
            case 'Spend Money':
                $docType = 26; //Fund Transfer;
                break;
            default:
                # code...
                break;
        }

        return $docType;
    }
    public function getCONTACT_NAME_VIA_DESCRIPTION(string $DESCRIPTION): string
    {
        $result = str_replace("Payment: ", "", $DESCRIPTION);
    
        return $result;
    }

}