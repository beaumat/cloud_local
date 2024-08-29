<?php

namespace App\Services;

use App\Models\Items;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class ItemServices
{
    use WithPagination;
    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function get($ID): object
    {
        return items::where('ID', $ID)->first();
    }
    public function getCost(int $ID): float
    {
        $item = items::select('COST')->find($ID);
        if ($item) {
            return $item->COST ?? 0;
        }
        return 0;
    }
    public function updateCost(int $ID, float $COST)
    {
        items::where('ID', $ID)
            ->update([
                'COST' => $COST
            ]);
    }
    public function AssemblyItem()
    {
        return items::where('TYPE', 1)
            ->where('INACTIVE', 0)
            ->get();
    }
    public function getInventoryItem(bool $isCode)
    {
        if ($isCode) {
            // Code
            return Items::query()
                ->select(['ID', 'CODE'])
                ->where('INACTIVE', '0')
                ->whereIn('TYPE', ['0', '1'])
                ->get();
        }
        // Descripton
        return Items::query()
            ->select(['ID', 'DESCRIPTION'])
            ->where('INACTIVE', '0')
            ->whereIn('TYPE', ['0', '1'])
            ->get();
    }
    public function getByVendor(bool $isCode)
    {
        if ($isCode) {
            return Items::query()
                ->select(['ID', 'CODE'])
                ->where('INACTIVE', '0')
                ->whereIn('TYPE', [0, 1])
                ->get();
        }
        return Items::query()
            ->select(
                [
                    'item.ID',
                    'item.DESCRIPTION'
                ]
            )->where('INACTIVE', '0')
            ->whereIn('TYPE', [0, 1])
            ->get();
    }
    public function getByCustomer(bool $isCode)
    {
        if ($isCode) {
            return Items::query()->select(['ID', 'CODE'])
                ->where('INACTIVE', '0')
                ->whereIn('TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
                ->get();
        }
        return Items::query()->select(['ID', 'DESCRIPTION'])
            ->where('INACTIVE', '0')
            ->whereIn('TYPE', [0, 1, 2, 3, 4, 5, 6, 7])
            ->get();
    }
    public function Store(
        string $CODE,
        string $DESCRIPTION,
        string $PURCHASE_DESCRIPTION,
        int $GROUP_ID,
        int $SUB_CLASS_ID,
        int $TYPE,
        int $STOCK_TYPE,
        int $GL_ACCOUNT_ID,
        int $COGS_ACCOUNT_ID,
        int $ASSET_ACCOUNT_ID,
        bool $TAXABLE,
        int $PREFERRED_VENDOR_ID,
        int $MANUFACTURER_ID,
        float $RATE,
        float $COST,
        int $RATE_TYPE,
        int $PAYMENT_METHOD_ID,
        string $NOTES,
        int $BASE_UNIT_ID,
        int $PURCHASES_UNIT_ID,
        int $SHIPPING_UNIT_ID,
        int $SALES_UNIT_ID,
        bool $PRINT_INDIVIDUAL_ITEMS,
        bool $INACTIVE,
        bool $NON_HEMO,
        bool $HEMO_NON_INVENTORY
    ): int {

        $ID = $this->object->ObjectNextID('ITEM');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('ITEM');
        Items::create([
            'ID'                        => $ID,
            'CODE'                      => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, null),
            'DESCRIPTION'               => $DESCRIPTION,
            'PURCHASE_DESCRIPTION'      => $PURCHASE_DESCRIPTION,
            'GROUP_ID'                  => $GROUP_ID > 0 ? $GROUP_ID : null,
            'SUB_CLASS_ID'              => $SUB_CLASS_ID > 0 ? $SUB_CLASS_ID : null,
            'TYPE'                      => $TYPE,
            'STOCK_TYPE'                => $STOCK_TYPE > 0 ? $STOCK_TYPE : null,
            'GL_ACCOUNT_ID'             => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
            'COGS_ACCOUNT_ID'           => $COGS_ACCOUNT_ID > 0 ? $COGS_ACCOUNT_ID : null,
            'ASSET_ACCOUNT_ID'          => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'TAXABLE'                   => $TAXABLE,
            'PREFERRED_VENDOR_ID'       => $PREFERRED_VENDOR_ID > 0 ? $PREFERRED_VENDOR_ID : null,
            'MANUFACTURER_ID'           => $MANUFACTURER_ID > 0 ? $MANUFACTURER_ID : null,
            'RATE'                      => $RATE,
            'COST'                      => $COST > 0 ? $COST : 0,
            'RATE_TYPE'                 => $RATE_TYPE,
            'PAYMENT_METHOD_ID'         => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
            'NOTES'                     => $NOTES,
            'BASE_UNIT_ID'              => $BASE_UNIT_ID > 0 ? $BASE_UNIT_ID : null,
            'PURCHASES_UNIT_ID'         => $PURCHASES_UNIT_ID > 0 ? $PURCHASES_UNIT_ID : null,
            'SHIPPING_UNIT_ID'          => $SHIPPING_UNIT_ID > 0 ? $SHIPPING_UNIT_ID : null,
            'SALES_UNIT_ID'             => $SALES_UNIT_ID > 0 ? $SALES_UNIT_ID : null,
            'PRINT_INDIVIDUAL_ITEMS'    => $PRINT_INDIVIDUAL_ITEMS,
            'INACTIVE'                  => $INACTIVE,
            'NON_HEMO'                  => $NON_HEMO,
            'HEMO_NON_INVENTORY'        => $HEMO_NON_INVENTORY
        ]);

        return $ID;
    }

    public function Update(
        int $ID,
        string $CODE,
        string $DESCRIPTION,
        string $PURCHASE_DESCRIPTION,
        int $GROUP_ID,
        int $SUB_CLASS_ID,
        int $TYPE,
        int $STOCK_TYPE,
        int $GL_ACCOUNT_ID,
        int $COGS_ACCOUNT_ID,
        int $ASSET_ACCOUNT_ID,
        bool $TAXABLE,
        int $PREFERRED_VENDOR_ID,
        int $MANUFACTURER_ID,
        float $RATE,
        float $COST,
        int $RATE_TYPE,
        int $PAYMENT_METHOD_ID,
        string $NOTES,
        int $BASE_UNIT_ID,
        int $PURCHASES_UNIT_ID,
        int $SHIPPING_UNIT_ID,
        int $SALES_UNIT_ID,
        bool $PRINT_INDIVIDUAL_ITEMS,
        bool $INACTIVE,
        bool $NON_HEMO,
        bool $HEMO_NON_INVENTORY
    ): void {

        Items::where('ID', $ID)
            ->update([
                'CODE'                      => $CODE,
                'DESCRIPTION'               => $DESCRIPTION,
                'PURCHASE_DESCRIPTION'      => $PURCHASE_DESCRIPTION,
                'GROUP_ID'                  => $GROUP_ID > 0 ? $GROUP_ID : null,
                'SUB_CLASS_ID'              => $SUB_CLASS_ID > 0 ? $SUB_CLASS_ID : null,
                'TYPE'                      => $TYPE,
                'STOCK_TYPE'                => $STOCK_TYPE > 0 ? $STOCK_TYPE : null,
                'GL_ACCOUNT_ID'             => $GL_ACCOUNT_ID > 0 ? $GL_ACCOUNT_ID : null,
                'COGS_ACCOUNT_ID'           => $COGS_ACCOUNT_ID > 0 ? $COGS_ACCOUNT_ID : null,
                'ASSET_ACCOUNT_ID'          => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
                'TAXABLE'                   => $TAXABLE,
                'PREFERRED_VENDOR_ID'       => $PREFERRED_VENDOR_ID > 0 ? $PREFERRED_VENDOR_ID : null,
                'MANUFACTURER_ID'           => $MANUFACTURER_ID > 0 ? $MANUFACTURER_ID : null,
                'RATE'                      => $RATE,
                'COST'                      => $COST > 0 ? $COST : null,
                'RATE_TYPE'                 => $RATE_TYPE,
                'PAYMENT_METHOD_ID'         => $PAYMENT_METHOD_ID > 0 ? $PAYMENT_METHOD_ID : null,
                'NOTES'                     => $NOTES,
                'BASE_UNIT_ID'              => $BASE_UNIT_ID > 0 ? $BASE_UNIT_ID : null,
                'PURCHASES_UNIT_ID'         => $PURCHASES_UNIT_ID > 0 ? $PURCHASES_UNIT_ID : null,
                'SHIPPING_UNIT_ID'          => $SHIPPING_UNIT_ID > 0 ? $SHIPPING_UNIT_ID : null,
                'SALES_UNIT_ID'             => $SALES_UNIT_ID > 0 ? $SALES_UNIT_ID : null,
                'PRINT_INDIVIDUAL_ITEMS'    => $PRINT_INDIVIDUAL_ITEMS,
                'INACTIVE'                  => $INACTIVE,
                'NON_HEMO'                  => $NON_HEMO,
                'HEMO_NON_INVENTORY'        => $HEMO_NON_INVENTORY
            ]);
    }

    public function Delete(int $ID): void
    {
        Items::where('ID', $ID)->delete();
    }
    public function Search($search, int $perPage)
    {
        return Items::query()
            ->select([
                'item.ID',
                'item.CODE',
                'item.DESCRIPTION',
                'item.TAXABLE',
                DB::raw('(CASE WHEN item.TYPE = 6 THEN (SELECT sum(c.RATE * c.QUANTITY) FROM item_components as c WHERE c.ITEM_ID = item.ID) ELSE item.RATE END) as RATE'),
                'item.COST',
                'item.INACTIVE',
                'item.COST',
                'item.INACTIVE',
                'item_type_map.DESCRIPTION as ITEM_TYPE',
                'item_sub_class.DESCRIPTION as SUB_CLASS',
                'item_sub_class.CLASS_ID',
                'item_class.DESCRIPTION as CLASS',
                'item_group.DESCRIPTION as GROUP_NAME',
                'stock_type_map.DESCRIPTION as STOCK_TYPE',
                'unit_of_measure.NAME as UNIT_BASE',
                'item.NON_HEMO',
                'item.HEMO_NON_INVENTORY'
            ])
            ->join('item_type_map', 'item_type_map.ID', '=', 'item.TYPE')
            ->leftJoin('item_sub_class', 'item_sub_class.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class', 'item_class.ID', '=', 'item_sub_class.CLASS_ID')
            ->leftJoin('item_group', 'item_group.ID', '=', 'item.GROUP_ID')
            ->leftJoin('stock_type_map', 'stock_type_map.ID', '=', 'item.STOCK_TYPE')
            ->leftJoin('unit_of_measure', 'unit_of_measure.ID', '=', 'item.BASE_UNIT_ID')
            ->where('item.INACTIVE', 0)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('item.CODE', 'like', '%' . $search . '%')
                        ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('item_type_map.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('item_sub_class.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('item_class.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('item_group.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('stock_type_map.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('unit_of_measure.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('item.ID', 'desc')
            ->paginate($perPage);
    }
    public function getOnhand(int $ITEM_ID, int $locationId): int
    {

        $items = DB::table('item')
            ->select(
                [
                    'item.ID',
                ]
            )
            ->selectSub(function ($query) use (&$locationId) {
                $query->from('item_inventory')->select('item_inventory.ENDING_QUANTITY')
                    ->whereColumn('item_inventory.ITEM_ID', 'item.ID')
                    ->where('item_inventory.LOCATION_ID', $locationId)
                    ->orderBy('item_inventory.SOURCE_REF_DATE', 'DESC')
                    ->orderBy('item_inventory.ID', 'DESC')->limit(1);
            }, 'QTY_ON_HAND')
            ->where('item.TYPE', '<', 2)
            ->where('item.INACTIVE', false)
            ->where('item.ID', $ITEM_ID)
            ->first();

        if ($items) {

            return (int) $items->QTY_ON_HAND ?? 0;
        }

        return 0;
    }
    public function getActiveItems($search, int $locationId, string $sortby, bool $isDesc)
    {


        $items = DB::table('item')
            ->select(
                [
                    'item.ID',
                    'item.CODE',
                    'item.DESCRIPTION',
                    'item.RATE',
                    'item.COST',
                    't.DESCRIPTION as TYPE',
                    'g.DESCRIPTION as GROUP_NAME',
                    'c.DESCRIPTION as CLASS_NAME',
                    's.DESCRIPTION as SUB_NAME',
                    'u.SYMBOL'
                ]
            )
            ->selectSub(function ($query) use (&$locationId) {
                $query->from('item_inventory')->select('item_inventory.ENDING_QUANTITY')
                    ->whereColumn('item_inventory.ITEM_ID', 'item.ID')
                    ->where('item_inventory.LOCATION_ID', $locationId)
                    ->orderBy('item_inventory.SOURCE_REF_DATE', 'DESC')
                    ->orderBy('item_inventory.ID', 'DESC')->limit(1);
            }, 'QTY_ON_HAND')
            ->leftJoin('item_type_map as t', 't.ID', '=', 'item.TYPE')
            ->leftJoin('item_group as g', 'g.ID', '=', 'item.GROUP_ID')
            ->leftJoin('item_sub_class as s', 's.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 's.CLASS_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', 'item.BASE_UNIT_ID')
            ->where('item.TYPE', '<', 2)
            ->where('item.INACTIVE', false)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('item.CODE', 'like', '%' . $search . '%')
                        ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('t.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('s.DESCRIPTION', 'like', '%' . $search . '%')
                        ->orWhere('c.DESCRIPTION', 'like', '%' . $search . '%');
                });
            })
            ->orderBy($sortby, $isDesc ? 'desc' : 'asc')
            ->get();

        return $items;
    }
    public function getItemListBySubId(int $itemSubId, $search, int $HEMO_ID)
    {
        $data = Items::query()
            ->select([
                'ID',
                'DESCRIPTION',
                'RATE'
            ])
            ->where('INACTIVE', 0)
            ->where('NON_HEMO', 0)
            ->where('SUB_CLASS_ID', $itemSubId)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('item.CODE', 'like', '%' . $search . '%')
                        ->orWhere('item.DESCRIPTION', 'like', '%' . $search . '%');
                });
            })
            ->whereNotExists(function ($query) use (&$HEMO_ID) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis_items as h')
                    ->whereRaw('h.ITEM_ID = item.ID')
                    ->where('h.IS_DEFAULT', 0)
                    ->where('h.HEMO_ID', $HEMO_ID);
            })
            ->orderBy('DESCRIPTION', 'asc')
            ->get();

        return $data;
    }
    public function PriceLevelItemList($search, int $SUB_CLASS_ID, int $LOCATION_ID)
    {

        $result = Items::query()
            ->select([
                'item.ID',
                'item.CODE',
                'item.DESCRIPTION',
                'sc.DESCRIPTION as SUB_CLASS',
                'c.DESCRIPTION as CLASS',
                DB::raw(' (select IFNULL(pll.CUSTOM_PRICE,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = item.ID and l.ID = ' . $LOCATION_ID . ' ) as PRICE')
            ])
            ->leftJoin('item_sub_class as sc', 'sc.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 'sc.CLASS_ID')
            ->where('item.INACTIVE', 0)
            ->when($SUB_CLASS_ID > 0, function ($query) use (&$SUB_CLASS_ID) {
                $query->where('item.SUB_CLASS_ID', $SUB_CLASS_ID);
            })
            ->where('item.DESCRIPTION', 'like', '%' . $search . '%')
            ->get();

        return $result;
    }
}
