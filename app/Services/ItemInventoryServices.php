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

    private function Store(
        int $PREVIOUS_ID,
        int $SEQUENCE_NO,
        int $ITEM_ID,
        int $LOCATION_ID,
        int $BATCH_ID,
        int $SOURCE_REF_TYPE,
        int $SOURCE_REF_ID,
        string $SOURCE_REF_DATE,
        float $QUANTITY,
        float $COST = 0,
        float $ENDING_QUANTITY = 0,
        float $ENDING_UNIT_COST = 0,
        float $ENDING_COST = 0
    ) {

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
    }

    private function Update(
        int $ITEM_ID,
        int $LOCATION_ID,
        int $SOURCE_REF_TYPE,
        int $SOURCE_REF_ID,
        string $SOURCE_REF_DATE,
        float $QUANTITY,
        float $COST = 0,
        float $ENDING_QUANTITY = 0,
        float $ENDING_UNIT_COST = 0,
        float $ENDING_COST = 0
    ) {

        ItemInventory::where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_ID)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->update([
                'QUANTITY' => $QUANTITY,
                'COST' => $COST,
                'ENDING_QUANTITY' => $ENDING_QUANTITY,
                'ENDING_UNIT_COST' => $ENDING_UNIT_COST,
                'ENDING_COST' => $ENDING_COST
            ]);
    }

    public function getPreviousItemInventory(int $LOCATION_ID, int $ITEM_ID, int $BATCH_ID, string $SOURCE_REF_DATE)
    {
        $currentInventorySubquery = DB::table('ITEM_INVENTORY')
            ->select('ITEM_ID', 'LOCATION_ID', 'BATCH_ID', DB::raw('MAX(SEQUENCE_NO) AS SEQUENCE_NO'))
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('BATCH_ID', $BATCH_ID)
            ->where('SOURCE_REF_DATE', '<=', $SOURCE_REF_DATE)
            ->groupBy('ITEM_ID', 'LOCATION_ID', 'BATCH_ID');

        $result = DB::table('ITEM_INVENTORY AS ITEM_INV')
            ->joinSub($currentInventorySubquery, 'CURRENT_INVENTORY', function ($join) {
                $join->on('CURRENT_INVENTORY.ITEM_ID', '=', 'ITEM_INV.ITEM_ID')
                    ->on('CURRENT_INVENTORY.LOCATION_ID', '=', 'ITEM_INV.LOCATION_ID')
                    ->on('CURRENT_INVENTORY.BATCH_ID', '=', 'ITEM_INV.BATCH_ID')
                    ->on('CURRENT_INVENTORY.SEQUENCE_NO', '=', 'ITEM_INV.SEQUENCE_NO');
            })
            ->leftJoin('ITEM_INVENTORY AS NEXT_INV', 'NEXT_INV.PREVIOUS_ID', '=', 'ITEM_INV.ID')
            ->select(
                'ITEM_INV.ID',
                'NEXT_INV.ID AS NEXT_ID',
                'NEXT_INV.QUANTITY AS NEXT_QUANTITY',
                'NEXT_INV.COST AS NEXT_COST'
            )
            ->first();

        if ($result) {
            return [
                'ID' => $result->ID,
                'NEXT_ID' => $result->NEXT_ID,
                'NEXT_QUANTITY' => $result->NEXT_QUANTITY,
                'NEXT_COST' => $result->NEXT_COST
            ];
        }


        return [
            'ID' => 0,
            'NEXT_ID' => 0,
            'NEXT_QUANTITY' => 0,
            'NEXT_COST' => 0
        ];
    }

    public function getAdavanceItemInventory(int $LOCATION_ID, int $ITEM_ID, int $BATCH_ID, string $SOURCE_REF_DATE)
    {
        // Subquery for CURRENT_INVENTORY
        $currentInventorySubquery = DB::table('ITEM_INVENTORY')
            ->select('ITEM_ID', 'LOCATION_ID', 'BATCH_ID', DB::raw('MIN(SEQUENCE_NO) AS SEQUENCE_NO'))
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('ITEM_ID', $ITEM_ID)
            ->where('BATCH_ID', $BATCH_ID)
            ->where('SOURCE_REF_DATE', '>', $SOURCE_REF_DATE)
            ->groupBy('ITEM_ID', 'LOCATION_ID', 'BATCH_ID');

        // Main query
        $query = DB::table('ITEM_INVENTORY AS ITEM_INV')
            ->joinSub($currentInventorySubquery, 'CURRENT_INVENTORY', function ($join) {
                $join->on('CURRENT_INVENTORY.ITEM_ID', '=', 'ITEM_INV.ITEM_ID')
                    ->on('CURRENT_INVENTORY.LOCATION_ID', '=', 'ITEM_INV.LOCATION_ID')
                    ->on('CURRENT_INVENTORY.BATCH_ID', '=', 'ITEM_INV.BATCH_ID')
                    ->on('CURRENT_INVENTORY.SEQUENCE_NO', '=', 'ITEM_INV.SEQUENCE_NO');
            })
            ->select(
                'ITEM_INV.ID',
                'ITEM_INV.QUANTITY',
                'ITEM_INV.COST'
            )
            ->first();

        return $query;
    }

    public function getEnding(int $ID)
    {
        $data = ItemInventory::query()
            ->select(
                [
                    'ID',
                    'SEQUENCE_NO',
                    'ENDING_QUANTITY',
                    'ENDING_UNIT_COST',
                    'ENDING_COST'
                ]
            )->where('ID', $ID)
            ->first();

        if ($data) {

            return [
                'ID' => $data->ID,
                'SEQUENCE_NO' => $data->SEQUENCE_NO,
                'ENDING_QUANTITY' => $data->ENDING_QUANTITY,
                'ENDING_UNIT_COST' => $data->ENDING_UNIT_COST,
                'ENDING_COST' => $data->ENDING_COST,
            ];
        }

        return [
            'ID' => 0,
            'SEQUENCE_NO' => -1,
            'ENDING_QUANTITY' => 0,
            'ENDING_UNIT_COST' => 0,
            'ENDING_COST' => 0,
        ];
    }

    private function InvItemExists(int $ITEM_ID, int $LOCATION_ID, int $SOURCE_REF_ID, int $SOURCE_REF_TYPE, string $SOURCE_REF_DATE): bool
    {
        return ItemInventory::query()
            ->where('ITEM_ID', $ITEM_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('SOURCE_REF_ID', $SOURCE_REF_ID)
            ->where('SOURCE_REF_TYPE', $SOURCE_REF_TYPE)
            ->where('SOURCE_REF_DATE', $SOURCE_REF_DATE)
            ->exists();
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


            if (isset($list->COST)) {
                $COST = (float) $list->COST ?? 0;
            } else {
                $COST = (float) $this->itemServices->getCost($ITEM_ID);
            }


            if (!$Is_Added) {
                $QTY = $QTY * -1;
            }

            $isExists = (bool) $this->InvItemExists($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE);

            if (!$isExists) {

                $PREV = $this->getPreviousItemInventory($LOCATION_ID, $ITEM_ID, $BATCH_ID, $SOURCE_REF_DATE);


                $PREVIOUS_ID = (int) $PREV['ID'];
                $NEXT_ID = $PREV['NEXT_ID'];
                $NEXT_QUANTITY = (int) $PREV['NEXT_QUANTITY'];
                $NEXT_COST = (int) $PREV['NEXT_COST'];


                $ending = $this->getEnding($PREVIOUS_ID);
                $SEQUENCE_NO = (int) $ending['SEQUENCE_NO'];
                $ENDING_QUANTITY = (float) $ending['ENDING_QUANTITY'] + $QTY;
                $ENDING_UNIT_COST = (float) $COST;
                $ENDING_COST = $ENDING_UNIT_COST * $ENDING_QUANTITY;

                $this->Store(
                    $PREVIOUS_ID,
                    $SEQUENCE_NO + 1,
                    $ITEM_ID,
                    $LOCATION_ID,
                    $BATCH_ID,
                    $SOURCE_REF_TYPE,
                    $SOURCE_REF_ID,
                    $SOURCE_REF_DATE,
                    $QTY,
                    $COST,
                    $ENDING_QUANTITY,
                    $ENDING_UNIT_COST,
                    $ENDING_COST
                );
            }
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

            // if (!$Is_Added) {
            //     $QTY = $QTY * -1;
            // }

            $isExists = (bool) $this->InvItemExists($ITEM_ID, $LOCATION_ID, $SOURCE_REF_ID, $SOURCE_REF_TYPE, $SOURCE_REF_DATE);

            if (!$isExists) {
                $PREV = $this->getPreviousItemInventory($LOCATION_ID, $ITEM_ID, $BATCH_ID, $SOURCE_REF_DATE);
                $PREVIOUS_ID = (int) $PREV['ID'];
                $NEXT_ID = $PREV['NEXT_ID'];
                $NEXT_QUANTITY = (int) $PREV['NEXT_QUANTITY'];
                $NEXT_COST = (int) $PREV['NEXT_COST'];
                $ending = $this->getEnding($PREVIOUS_ID);
                $SEQUENCE_NO = (int) $ending['SEQUENCE_NO'];
                $QTY = (float) $ENDING_QUANTITY - (float) $ending['ENDING_QUANTITY'];


                $ENDING_UNIT_COST = (float) $COST;
                $ENDING_COST = $ENDING_UNIT_COST * $ENDING_QUANTITY;
                $this->Store(
                    $PREVIOUS_ID,
                    $SEQUENCE_NO + 1,
                    $ITEM_ID,
                    $LOCATION_ID,
                    $BATCH_ID,
                    $SOURCE_REF_TYPE,
                    $SOURCE_REF_ID,
                    $SOURCE_REF_DATE,
                    $QTY,
                    $COST,
                    $ENDING_QUANTITY,
                    $ENDING_UNIT_COST,
                    $ENDING_COST
                );
            }
        }
    }
}
