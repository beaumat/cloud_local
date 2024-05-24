<?php

namespace App\Services;

use App\Models\StockTransfer;
use App\Models\StockTransferItems;

class StockTransferServices
{
    private $dateServices;
    private $systemSettingServices;
    private $object;
    public function __construct(ObjectServices $objectServices, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function Get(int $ID)
    {
        return StockTransfer::where('ID', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $TRANSFER_TO_ID, string $NOTES, int $PREPARED_BY_ID, int $ACCOUNT_ID): int
    {

        $ID = $this->object->ObjectNextID('STOCK_TRANSFER');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('STOCK_TRANSFER');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        StockTransfer::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'LOCATION_ID' => $LOCATION_ID,
            'TRANSFER_TO_ID' => $TRANSFER_TO_ID,
            'AMOUNT' => 0,
            'RETAIL_VALUE' => 0,
            'NOTES' => $NOTES,
            'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
            'STATUS' => 0,
            'ACCOUNT_ID' => $ACCOUNT_ID
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $TRANSFER_TO_ID, string $NOTES, int $PREPARED_BY_ID)
    {

        StockTransfer::where('ID', $ID)
            ->update([
                'CODE' => $CODE,
                'TRANSFER_TO_ID' => $TRANSFER_TO_ID,
                'NOTES' => $NOTES,
                'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,

            ]);
    }

    public function Delete(int $ID)
    {
        StockTransferItems::where('STOCK_TRANSFER_ID', $ID)->delete();
        StockTransfer::where('ID', $ID)->delete();
    }

    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = StockTransfer::query()
            ->select([
                'stock_transfer.ID',
                'stock_transfer.CODE',
                'stock_transfer.DATE',
                'stock_transfer.AMOUNT',
                'stock_transfer.RETAIL_VALUE',
                'stock_transfer.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                't.NAME as TRANSFER_TO',
                'c.NAME as PREPARED_BY'
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'stock_transfer.PREPARED_BY_ID')
            ->join('location as t', 't.ID', '=', 'stock_transfer.TRANSFER_TO_ID')
            ->join('document_status_map as s', 's.ID', '=', 'stock_transfer.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'stock_transfer.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('stock_transfer.CODE', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('stock_transfer.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('stock_transfer.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

}