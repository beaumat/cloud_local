<?php

namespace App\Services;

use App\Models\InventoryAdjustment;
use App\Models\InventoryAdjustmentItems;

class InventoryAdjustmentServices
{
    private $object;
    private $compute;
    private $systemSettingServices;
    private $dateServices;

    public function __construct(
        ObjectServices $objectService,
        ComputeServices $computeServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices
    ) {
        $this->object = $objectService;
        $this->compute = $computeServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;

    }
    public function Get(int $ID)
    {
        return InventoryAdjustment::where('ID', $ID)->first();
    }
    public function Store(string $CODE, string $DATE, int $LOCATION_ID, int $ADJUSTMENT_TYPE_ID, int $ACCOUNT_ID, string $NOTES): int
    {
        $ID = (int) $this->object->ObjectNextID('INVENTORY_ADJUSTMENT');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('INVENTORY_ADJUSTMENT');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        InventoryAdjustment::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $DATE,
            'LOCATION_ID' => $LOCATION_ID,
            'ADJUSTMENT_TYPE_ID' => $ADJUSTMENT_TYPE_ID,
            'ACCOUNT_ID' => $ACCOUNT_ID,
            'NOTES' => $NOTES,
            'STATUS' => 0,
            'STATUS_DATE' => $this->dateServices->NowDate(),
        ]);

        return $ID;
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        InventoryAdjustment::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS,
                'STATUS_DATE' => $this->dateServices->NowDate()
            ]);
    }
    public function Update(int $ID, string $CODE, int $LOCATION_ID, int $ADJUSTMENT_TYPE_ID, int $ACCOUNT_ID, string $NOTES)
    {
        InventoryAdjustment::where('ID', $ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'CODE' => $CODE,
                'ADJUSTMENT_TYPE_ID' => $ADJUSTMENT_TYPE_ID,
                'ACCOUNT_ID' => $ACCOUNT_ID,
                'NOTES' => $NOTES
            ]);
    }
    public function Delete(int $ID)
    {
        InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $ID)->delete();
        InventoryAdjustment::where('ID', $ID)->delete();
    }
    public function Search($search, int $locationId, int $perPage)
    {
        $result = InventoryAdjustment::query()
            ->select([
                'inventory_adjustment.ID',
                'inventory_adjustment.CODE',
                'inventory_adjustment.DATE',
                'inventory_adjustment.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                't.DESCRIPTION as TYPE'
            ])
            ->join('inventory_adjustment_type as t', 't.ID', '=', 'inventory_adjustment.ADJUSTMENT_TYPE_ID')
            ->join('location as l', function ($join) use (&$locationId) {
                $join->on('l.ID', '=', 'inventory_adjustment.LOCATION_ID');
                if ($locationId > 0) {
                    $join->where('l.ID', $locationId);
                }
            })
            ->join('document_status_map as s', 's.ID', '=', 'inventory_adjustment.STATUS')
            ->when($search, function ($query) use (&$search) {
                $query->where('inventory_adjustment.CODE', 'like', '%' . $search . '%')
                    ->where('t.DESCRIPTION', 'like', '%' . $search . '%')
                    ->orWhere('inventory_adjustment.NOTES', 'like', '%' . $search . '%');

            })
            ->orderBy('inventory_adjustment.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }
    public function CountItems(int $INVENTORY_ADJUSTMENT_ID): int
    {
        return (int) InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)->count();
    }
    private function getLine($INVENTORY_ADJUSTMENT_ID): int
    {
        return (int) InventoryAdjustmentItems::where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)->max('LINE_NO');
    }
    public function GetItem(int $ID, int $INVENTORY_ADJUSTMENT_ID)
    {
        return InventoryAdjustmentItems::where('ID', $ID)
            ->where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->first();

    }
    public function ItemStore(
        int $INVENTORY_ADJUSTMENT_ID,
        int $ITEM_ID,
        float $QUANTITY,
        float $UNIT_COST,
        int $ASSET_ACCOUNT_ID,
        int $BATCH_ID,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY
    ) {

        $ID = (int) $this->object->ObjectNextID('INVENTORY_ADJUSTMENT_ITEMS');

        $LINE_NO = (int) $this->getLine($INVENTORY_ADJUSTMENT_ID) + 1;

        InventoryAdjustmentItems::create([
            'ID' => $ID,
            'INVENTORY_ADJUSTMENT_ID' => $INVENTORY_ADJUSTMENT_ID,
            'LINE_NO' => $LINE_NO,
            'ITEM_ID' => $ITEM_ID,
            'QUANTITY' => $QUANTITY,
            'UNIT_COST' => $UNIT_COST,
            'QTY_DIFFERENCE' => 0,
            'VALUE_DIFFERENCE' => 0,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID,
            'ASSET_VALUE' => 0,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY
        ]);

    }
    public function ItemUpdate(
        int $ID,
        int $INVENTORY_ADJUSTMENT_ID,
        int $ITEM_ID,
        float $QUANTITY,
        float $UNIT_COST,
        int $BATCH_ID,
        int $UNIT_ID,
        float $UNIT_BASE_QUANTITY
    ) {
        InventoryAdjustmentItems::where('ID', $ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->update([
                'QUANTITY' => $QUANTITY,
                'UNIT_COST' => $UNIT_COST,
                'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
                'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY
            ]);
    }
    public function ItemDelete(
        int $ID,
        int $INVENTORY_ADJUSTMENT_ID
    ) {
        InventoryAdjustmentItems::where('ID', $ID)
            ->where('INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->delete();
    }

    public function ItemView(int $INVENTORY_ADJUSTMENT_ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ITEM_ID',
                'inventory_adjustment_items.QUANTITY',
                'inventory_adjustment_items.UNIT_COST',
                'inventory_adjustment_items.UNIT_ID',
                'inventory_adjustment_items.QTY_DIFFERENCE',
                'inventory_adjustment_items.ASSET_VALUE',
                'inventory_adjustment_items.VALUE_DIFFERENCE',
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL'
            ])
            ->leftJoin('item', 'item.ID', '=', 'inventory_adjustment_items.ITEM_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'inventory_adjustment_items.UNIT_ID')
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->orderBy('inventory_adjustment_items.LINE_NO', 'asc')
            ->get();

        return $result;
    }

    public function ItemInventory(int $INVENTORY_ADJUSTMENT_ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ITEM_ID',
                'inventory_adjustment_items.QUANTITY',
                'inventory_adjustment_items.UNIT_BASE_QUANTITY',
                'item.COST'
            ])
            ->join('item', 'item.ID', '=', 'inventory_adjustment_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', $INVENTORY_ADJUSTMENT_ID)
            ->get();

        return $result;

    }
    public function getInventoryAdjustmentJournal(int $ID)
    {   

        $result = InventoryAdjustment::query()
            ->select([
                'inventory_adjustment.ID',
                'inventory_adjustment.ACCOUNT_ID',
                'inventory_adjustment.ADJUSTMENT_TYPE_ID as SUBSIDIARY_ID',
                \DB::raw('(SELECT IFNULL(SUM(items.QUANTITY * items.UNIT_COST), 0) FROM inventory_adjustment_items as items WHERE items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID) as AMOUNT'),
                \DB::raw('IF((SELECT IFNULL(SUM(items.QUANTITY * items.UNIT_COST), 0) FROM inventory_adjustment_items as items WHERE items.INVENTORY_ADJUSTMENT_ID = inventory_adjustment.ID) >= 0, 1, 0) as ENTRY_TYPE')
            ])
            ->where('inventory_adjustment.ID', $ID)
            ->get();

        return $result;
    }
    public function getInventoryAdjustmentItemsJournal(int $ID)
    {
        $result = InventoryAdjustmentItems::query()
            ->select([
                'inventory_adjustment_items.ID',
                'inventory_adjustment_items.ASSET_ACCOUNT_ID as ACCOUNT_ID',
                'inventory_adjustment_items.ITEM_ID as SUBSIDIARY_ID',
                \DB::raw('IFNULL(inventory_adjustment_items.QUANTITY * inventory_adjustment_items.UNIT_COST, 0) as AMOUNT'),
                \DB::raw('IF(IFNULL(inventory_adjustment_items.QUANTITY * inventory_adjustment_items.UNIT_COST, 0) >= 0, 0, 1) as ENTRY_TYPE')
            ])
            ->where('inventory_adjustment_items.INVENTORY_ADJUSTMENT_ID', $ID)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
}