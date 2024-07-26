<?php

namespace App\Services;

use App\Models\ItemInventory;
use Illuminate\Support\Facades\DB;

class ItemInventoryServices
{
    private $object;
    private $itemServices;
    public function __construct(ObjectServices $objectService, ItemServices $itemServices)
    {
        $this->object = $objectService;
        $this->itemServices = $itemServices;
    }

    private function Store(int $PREVIOUS_ID, int $SEQUENCE_NO, int $ITEM_ID, int $LOCATION_ID, int $BATCH_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $QUANTITY, float $COST = 0, float $ENDING_QUANTITY = 0, float $ENDING_UNIT_COST = 0, float $ENDING_COST = 0)
    {

        $ID = (int) $this->object->ObjectNextID('ITEM_INVENTORY');
        ItemInventory::create([
            'ID' => $ID,
            'PREVIOUS_ID' => $PREVIOUS_ID > 0 ? $PREVIOUS_ID : null,
            'SEQUENCE_NO' => $SEQUENCE_NO,
            'ITEM_ID' => $ITEM_ID,
            'LOCATION_ID' => $LOCATION_ID,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : 0,
            'SOURCE_REF_TYPE' => $SOURCE_REF_TYPE,
            'SOURCE_REF_ID' => $SOURCE_REF_ID,
            'SOURCE_REF_DATE' => $SOURCE_REF_DATE,
            'QUANTITY' => $QUANTITY,
            'COST' => $COST,
            'ENDING_QUANTITY' => $ENDING_QUANTITY,
            'ENDING_UNIT_COST' => $ENDING_UNIT_COST,
            'ENDING_COST' => $ENDING_COST
        ]);



        $nextData =  $this->getNextEndingStore($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);

        foreach ($nextData as $list) {
            $ENDING_QUANTITY = $ENDING_QUANTITY + (float) $list->QUANTITY ?? 0;
            $NEW_ENDING_COST = $ENDING_QUANTITY + (float) $list->ENDING_UNIT_COST ?? 0;
            $this->getNextUpdate($list->ID, $ITEM_ID, $LOCATION_ID, $list->SOURCE_REF_TYPE, $list->SOURCE_REF_ID, $list->SOURCE_REF_DATE, $ENDING_QUANTITY, $NEW_ENDING_COST);
        }
    }
    private function Update(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $QUANTITY)
    {


        $data = ItemInventory::where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE);

        $result =  $data->first();

        if ($result) {

            $ID = $result->ID;

            $preResult = $this->getPreviousEnding($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE, $ID);

            $PREV_END_QTY = (float) $preResult['ENDING_QUANTITY'];
            $PREV_END_COST = (float) $preResult['ENDING_COST'];

            $ENDING_QUANTITY = $PREV_END_QTY +  $QUANTITY;
            $ENDING_COST = $PREV_END_COST * $ENDING_QUANTITY;
            $data->update(['QUANTITY' => $QUANTITY, 'ENDING_QUANTITY' => $ENDING_QUANTITY, 'ENDING_COST' => $ENDING_COST]);

            $nextData = $this->getNextEndingUpdate($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE, $ID);

            foreach ($nextData as $list) {
                $ENDING_QUANTITY = $ENDING_QUANTITY + (float) $list->QUANTITY ?? 0;
                $NEW_ENDING_COST = $ENDING_QUANTITY + (float) $list->ENDING_UNIT_COST ?? 0;
                $this->getNextUpdate($list->ID, $ITEM_ID, $LOCATION_ID, $list->SOURCE_REF_TYPE, $list->SOURCE_REF_ID, $list->SOURCE_REF_DATE, $ENDING_QUANTITY, $NEW_ENDING_COST);
            }
        }
    }
    private function getNextEndingUpdate(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE, int $ID)
    {

        $result = DB::table('item_inventory')
            ->select([
                'ID',
                'QUANTITY',
                'ENDING_UNIT_COST',
                'SOURCE_REF_TYPE',
                'SOURCE_REF_ID',
                'SOURCE_REF_DATE'
            ])
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '>=', $SOURCE_REF_DATE)
            ->where('ID', '>', $ID)
            ->orderBy('SOURCE_REF_DATE', 'asc')
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
    private function getNextEndingStore(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE)
    {

        $result = DB::table('item_inventory')
            ->select([
                'ID',
                'QUANTITY',
                'ENDING_UNIT_COST',
                'SOURCE_REF_TYPE',
                'SOURCE_REF_ID',
                'SOURCE_REF_DATE'
            ])
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '>', $SOURCE_REF_DATE)
            ->orderBy('SOURCE_REF_DATE', 'asc')
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
    private function getNextUpdate(int $ID, int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_TYPE, int $SOURCE_REF_ID, string $SOURCE_REF_DATE, float $ENDING_QUANTITY, float $ENDING_COST)
    {

        ItemInventory::where('ID', $ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->update([
                'ENDING_QUANTITY' => $ENDING_QUANTITY,
                'ENDING_COST'     => $ENDING_COST
            ]);
    }
    private function getPreviousEnding(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE, int $ID): array
    {
        try {
            $prevData = DB::table('item_inventory')
                ->select([
                    'ENDING_QUANTITY',
                    'ENDING_COST'
                ])
                ->where('ITEM_ID', $ITEM_ID)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
                ->where('ID', '<', $ID)
                ->orderBy('SOURCE_REF_DATE', 'desc')
                ->orderBy('ID', 'desc')
                ->limit(1)
                ->first();

            if ($prevData) {

                return  [
                    'ENDING_QUANTITY' => $prevData->ENDING_QUANTITY ?? 0,
                    'ENDING_COST' => $prevData->ENDING_COST ?? 0
                ];
            }
            return  [
                'ENDING_QUANTITY' => 0,
                'ENDING_COST' => 0
            ];
        } catch (\Throwable $th) {

            return  [
                'ENDING_QUANTITY' => 0,
                'ENDING_COST' => 0
            ];
        }
    }
    public function getPreviousID(int $LOCATION_ID, int $ITEM_ID): int
    {
        $result = DB::table('item_inventory')
            ->select(['ID'])
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($result) {
            return $result->ID ?? 0;
        }
        return 0;
    }
   
    private function getEndingLastOutPut(int $ITEM_ID, int $LOCATION_ID, string $SOURCE_REF_DATE)
    {
        $data = DB::table('item_inventory')
            ->select([
                'ID',
                'SEQUENCE_NO',
                'ENDING_QUANTITY',
                'ENDING_UNIT_COST',
                'ENDING_COST'
            ])
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
            ->orderBy('SOURCE_REF_DATE', 'desc')
            ->orderBy('ID', 'desc')
            ->limit(1)
            ->first();

        if ($data) {
            return [
                'ID'                => $data->ID,
                'SEQUENCE_NO'       => $data->SEQUENCE_NO,
                'ENDING_QUANTITY'   => $data->ENDING_QUANTITY,
                'ENDING_UNIT_COST'  => $data->ENDING_UNIT_COST,
                'ENDING_COST'       => $data->ENDING_COST,
            ];
        }

        return [
            'ID'                => 0,
            'SEQUENCE_NO'       => -1,
            'ENDING_QUANTITY'   => 0,
            'ENDING_UNIT_COST'  => 0,
            'ENDING_COST'       => 0,
        ];
    }

    private function InvItemExists(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE): bool
    {
        return (bool) ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->exists();
    }
    private function getInvItem(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE)
    {
        $result = ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->first();

        return $result;
    }

    public function InventoryModify(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE, int $BATCH_ID, float $QTY, float $COST)
    {
        $isInventoryExists = (bool) DB::table('item')->where('ID', $ITEM_ID)->whereIn('TYPE', [0, 1])->exists();

        if (!$isInventoryExists) {

            return;
        }


        $dataExist =  $this->getInvItem($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE);

        if (!$dataExist) {
            // new store
            $PREVIOUS_ID        = $this->getPreviousID($LOCATION_ID, $ITEM_ID); // FIXED
            $ENDING_ARRAY        = $this->getEndingLastOutPut($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
            $SEQUENCE_NO        = (int) $ENDING_ARRAY['SEQUENCE_NO'];
            $ENDING_QUANTITY    = (float) $ENDING_ARRAY['ENDING_QUANTITY'] + $QTY;
            $ENDING_UNIT_COST   = (float) $COST;
            $ENDING_COST        = $ENDING_UNIT_COST * $ENDING_QUANTITY;

            $this->Store($PREVIOUS_ID, $SEQUENCE_NO + 1, $ITEM_ID, $LOCATION_ID, $BATCH_ID, $SOURCE_REF_TYPE, $SOURCE_REF_ID, $SOURCE_REF_DATE, $QTY, $COST, $ENDING_QUANTITY, $ENDING_UNIT_COST, $ENDING_COST);
            return;
        }
        // update 
        $this->Update($ITEM_ID, $LOCATION_ID, $SOURCE_REF_TYPE, $SOURCE_REF_ID, $SOURCE_REF_DATE, $QTY);
    }

    public function InventoryExecute($data, int $LOCATION_ID, int $SOURCE_REF_TYPE, $SOURCE_REF_DATE, bool $Is_Added)
    {

        foreach ($data as $list) {

            $SOURCE_REF_ID = (int) $list->ID;
            $ITEM_ID = (int) $list->ITEM_ID;
            $QUANTITY = (float) $list->QUANTITY ?? 1;
            $BATCH_ID = $list->BATCH_ID ?? 0;
            $UNIT_BASE_QUANTITY = (float) $list->UNIT_BASE_QUANTITY ?? 1;

            $QTY = (float) $QUANTITY * $UNIT_BASE_QUANTITY;

            if (!$Is_Added) {
                $QTY = $QTY * -1;
            }

            if (isset($list->COST)) {
                $COST = (float) $list->COST ?? 0;
            } else {
                $COST = (float) $this->itemServices->getCost($ITEM_ID);
            }

            $this->InventoryModify($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE, $BATCH_ID, $QTY, $COST);
        }
    }

    public function InventoryExecuteAdjustment($data, int $LOCATION_ID, int $SOURCE_REF_TYPE, $SOURCE_REF_DATE)
    {

        foreach ($data as $list) {

            $SOURCE_REF_ID = (int) $list->ID;
            $ITEM_ID = (int) $list->ITEM_ID;
            $QUANTITY = (float) $list->QUANTITY ?? 1;
            $BATCH_ID = $list->BATCH_ID ?? 0;
            $UNIT_BASE_QUANTITY = (float) $list->UNIT_BASE_QUANTITY ?? 1;
            $ENDING_QUANTITY = (float) $QUANTITY * $UNIT_BASE_QUANTITY;

            if (isset($list->COST)) {
                $COST = (float) $list->COST ?? 0;
            } else {
                $COST = (float) $this->itemServices->getCost($ITEM_ID);
            }

            $isExists = (bool) $this->InvItemExists($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE);

            if (!$isExists) {
                $PREVIOUS_ID = $this->getPreviousID($LOCATION_ID, $ITEM_ID);             
                $ending = $this->getEndingLastOutPut($ITEM_ID, $LOCATION_ID, $SOURCE_REF_DATE);
                $SEQUENCE_NO = (int) $ending['SEQUENCE_NO'];
                $QTY = (float) $ENDING_QUANTITY - (float) $ending['ENDING_QUANTITY'];

                $ENDING_UNIT_COST = (float) $COST;
                $ENDING_COST = $ENDING_UNIT_COST * $ENDING_QUANTITY;
                $this->Store( $PREVIOUS_ID, $SEQUENCE_NO + 1, $ITEM_ID, $LOCATION_ID, $BATCH_ID, $SOURCE_REF_TYPE, $SOURCE_REF_ID, $SOURCE_REF_DATE, $QTY, $COST, $ENDING_QUANTITY, $ENDING_UNIT_COST, $ENDING_COST );
            }
        }
    }

    public function getDetails(int $ITEM_ID, int $LOCATION_ID)
    {
        $result = ItemInventory::query()
            ->select([
                'item_inventory.ID',
                't.DESCRIPTION as TYPE',
                'item_inventory.SOURCE_REF_DATE',
                'item_inventory.QUANTITY',
                'item_inventory.ENDING_QUANTITY'
            ])
            ->join('document_type_map as t', 't.ID', '=', 'item_inventory.SOURCE_REF_TYPE')
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->orderBy('item_inventory.SOURCE_REF_DATE', 'asc')
            ->orderBy('item_inventory.ID', 'asc')
            ->get();


        return $result;
    }
}
