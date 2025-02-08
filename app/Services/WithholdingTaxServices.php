<?php

namespace App\Services;

use App\Models\WithholdingTax;
use Illuminate\Pagination\LengthAwarePaginator;

class WithholdingTaxServices
{
    private $object;
    private $systemSettingServices;
    private $dateServices;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }

    public function Store(string $CODE, string $DATE, int $WITHHELD_FROM_ID, float $EWT_RATE, int $EWT_ID, int $EWT_ACCOUNT_ID, int  $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID)
    {

        $ID  = (int) $this->object->ObjectNextID('WITHHOLDING_TAX');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('WITHHOLDING_TAX');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        WithholdingTax::create([
            'ID'                    => $ID,
            'RECORDED_ON'           => $this->dateServices->Now(),
            'CODE'                  => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'                  => $DATE,
            'WITHHELD_FROM_ID'      => $WITHHELD_FROM_ID,
            'EWT_ID'                => $EWT_ID,
            'EWT_RATE'              => $EWT_RATE,
            'EWT_ACCOUNT_ID'        => $EWT_ACCOUNT_ID,
            'LOCATION_ID'           => $LOCATION_ID,
            'AMOUNT'                => 0,
            'NOTES'                 => $NOTES,
            'STATUS'                => 0,
            'STATUS_DATE'           => $this->dateServices->NowDate(),
            'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID
        ]);
    }

    public function Update(int $ID, $CODE, int $WITHHELD_FROM_ID, float $EWT_RATE, int $EWT_ID, int $EWT_ACCOUNT_ID, int  $LOCATION_ID, string $NOTES, int $ACCOUNTS_PAYABLE_ID)
    {

        WithholdingTax::where('ID', '=', $ID)
            ->update([
                'CODE'                  => $CODE,
                'WITHHELD_FROM_ID'      => $WITHHELD_FROM_ID,
                'EWT_ID'                => $EWT_ID,
                'EWT_RATE'              => $EWT_RATE,
                'EWT_ACCOUNT_ID'        => $EWT_ACCOUNT_ID,
                'LOCATION_ID'           => $LOCATION_ID,
                'NOTES'                 => $NOTES,
                'ACCOUNTS_PAYABLE_ID' => $ACCOUNTS_PAYABLE_ID
            ]);
    }

    public function Delete(int $ID)
    {
        WithholdingTax::where('ID', '=', $ID)->delete();
    }

    public function Search($search, int $LOCATION_ID, int $perPage): LengthAwarePaginator
    {

        $result = WithholdingTax::query()
            ->select([
                'withholding_tax.ID',
                'withholding_tax.CODE',
                'withholding_tax.DATE',
                'withholding_tax.AMOUNT',
                'withholding_tax.NOTES',
                'withholding_tax.EWT_RATE',
                'c.PRINT_NAME_AS as NAME',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS'

            ])
            ->join('contact as c', 'c.ID', '=', 'withholding_tax.WITHHELD_FROM_ID')
            ->join('account as a', 'a.ID', '=', 'withholding_tax.EWT_ACCOUNT_ID')
            ->join('document_status_map as s', 's.ID', '=', 'withholding_tax.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'withholding_tax.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->join('tax as t', 't.ID', '=', 'withholding_tax.EWT_ID')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('withholding_tax.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('withholding_tax.AMOUNT', 'like', '%' . $search . '%')
                        ->orWhere('withholding_tax.NOTES', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('withholding_tax.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
}
