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
    public function Store(
        string $CODE,
        string $DATE,
        int $LOCATION_ID,
        int $TRANSFER_TO_ID,
        string $NOTES,
        int $PREPARED_BY_ID,
        int $ACCOUNT_ID
    ): int {

        $ID = $this->object->ObjectNextID('STOCK_TRANSFER');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('STOCK_TRANSFER');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        StockTransfer::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'LOCATION_ID' => $LOCATION_ID,
            'TRANSFER_TO_ID' => $TRANSFER_TO_ID > 0 ? $TRANSFER_TO_ID : null,
            'AMOUNT' => 0,
            'RETAIL_VALUE' => 0,
            'NOTES' => $NOTES,
            'PREPARED_BY_ID' => $PREPARED_BY_ID > 0 ? $PREPARED_BY_ID : null,
            'STATUS' => 0,
            'ACCOUNT_ID' => $ACCOUNT_ID
        ]);

        return $ID;
    }
    public function Update(
        int $ID,
        string $CODE,
        int $TRANSFER_TO_ID,
        string $NOTES,
        int $PREPARED_BY_ID
    ) {
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
    private function getLine($STOCK_TRANSFER_ID): int
    {
        return (int) StockTransferItems::where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)->max('LINE_NO');
    }
    public function ItemStore(
        int $STOCK_TRANSFER_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $UNIT_COST,
        float $UNIT_PRICE,
        int $BATCH_ID,
        int $ASSET_ACCOUNT_ID
    ) {

        $ID = $this->object->ObjectNextID('STOCK_TRANSFER_ITEMS');

        $LINE_NO = $this->getLine($STOCK_TRANSFER_ID) + 1;

        StockTransferItems::create([
            'ID' => $ID,
            'STOCK_TRANSFER_ID' => $STOCK_TRANSFER_ID,
            'LINE_NO' => $LINE_NO,
            'ITEM_ID' => $ITEM_ID,
            'DESCRIPTION' => null,
            'QUANTITY' => $QUANTITY,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'UNIT_COST' => $UNIT_COST,
            'UNIT_PRICE' => $UNIT_PRICE,
            'AMOUNT' => $UNIT_COST * $QUANTITY,
            'RETAIL_VALUE' => $UNIT_PRICE * $QUANTITY,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null
        ]);

        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }

    public function ItemUpdate(
        int $ID,
        int $STOCK_TRANSFER_ID,
        int $ITEM_ID,
        float $QUANTITY,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY,
        float $UNIT_COST,
        float $UNIT_PRICE,
        int $BATCH_ID
    ) {
        StockTransferItems::where('ID', $ID)
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->update([
                'ID' => $ID,
                'ITEM_ID' => $ITEM_ID,
                'QUANTITY' => $QUANTITY,
                'UNIT_ID' => $UNIT_ID,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'UNIT_COST' => $UNIT_COST,
                'UNIT_PRICE' => $UNIT_PRICE,
                'AMOUNT' => $UNIT_COST * $QUANTITY,
                'RETAIL_VALUE' => $UNIT_PRICE * $QUANTITY,
                'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null
            ]);

        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }
    public function ItemDelete(
        int $ID,
        int $STOCK_TRANSFER_ID,
    ) {
        StockTransferItems::where('ID', $ID)
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->delete();

        $this->UpdateTotal($STOCK_TRANSFER_ID);
    }
    public function ItemView(int $STOCK_TRANSFER_ID)
    {
        $result = StockTransferItems::query()
            ->select([
                'stock_transfer_items.ID',
                'stock_transfer_items.ITEM_ID',
                'stock_transfer_items.QUANTITY',
                'stock_transfer_items.UNIT_ID',
                'stock_transfer_items.UNIT_COST',
                'stock_transfer_items.UNIT_PRICE',
                'stock_transfer_items.AMOUNT',
                'stock_transfer_items.RETAIL_VALUE',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL'
            ])
            ->leftJoin('item', 'item.ID', '=', 'stock_transfer_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'stock_transfer_items.UNIT_ID')
            ->where('stock_transfer_items.STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->orderBy('stock_transfer_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }
    private function GetTotal(int $STOCK_TRANSFER_ID)
    {

        $result = StockTransferItems::query()
            ->select([
                \DB::raw(' ifnull( sum(AMOUNT),2) as AMOUNT'),
                \DB::raw(' ifnull( sum(RETAIL_VALUE),2) as RETAIL_VALUE'),
            ])
            ->where('STOCK_TRANSFER_ID', $STOCK_TRANSFER_ID)
            ->first();

        if ($result) {
            return [
                'AMOUNT' => $result->AMOUNT,
                'RETAIL_VALUE' => $result->RETAIL_VALUE
            ];
        }

        return [
            'AMOUNT' => 0,
            'RETAIL_VALUE' => 0
        ];
    }
    public function UpdateTotal(int $STOCK_TRANSFER_ID)
    {
        $result = $this->GetTotal($STOCK_TRANSFER_ID);
        StockTransfer::where('ID', $STOCK_TRANSFER_ID)
            ->update(
                [
                    'AMOUNT' => $result['AMOUNT'],
                    'RETAIL_VALUE' => $result['RETAIL_VALUE'],

                ]
            );
    }
}