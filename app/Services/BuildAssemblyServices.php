<?php

namespace App\Services;

use App\Models\BuildAssembly;
use App\Models\BuildAssemblyItems;

class BuildAssemblyServices
{
    private $object;
    private $systemSettingServices;
    private $dateServices;
    public function __construct(ObjectServices $objectService, SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->object = $objectService;
        $this->systemSettingServices = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function Get(int $ID)
    {
        return BuildAssembly::where('ID', $ID)->first();
    }
    public function Store(
        string $CODE,
        string $DATE,
        int $LOCATION_ID,
        int $ASSEMBLY_ITEM_ID,
        float $QUANTITY,
        int $BATCH_ID,
        int $UNIT_ID,
        int $UNIT_BASE_QUANTITY,
        string $NOTES,
        int $ASSET_ACCOUNT_ID
    ): int {

        $ID = (int) $this->object->ObjectNextID('BUILD_ASSEMBLY');
        $OBJECT_TYPE = (int) $this->object->ObjectTypeID('BUILD_ASSEMBLY');
        $isLocRef = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        BuildAssembly::create([
            'ID' => $ID,
            'RECORDED_ON' => $this->dateServices->Now(),
            'CODE' => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE' => $this->dateServices->NowDate(),
            'LOCATION_ID' => $LOCATION_ID,
            'ASSEMBLY_ITEM_ID' => $ASSEMBLY_ITEM_ID,
            'QUANTITY' => $QUANTITY,
            'AMOUNT' => 0,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
            'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'NOTES' => $NOTES,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : null,
            'STATUS' => 0,
        ]);

        return (int) $ID;
    }

    public function Update(
        int $ID,
        string $CODE,
        float $QUANTITY,
        int $BATCH_ID,
        int $UNIT_ID,
        int $UNIT_BASE_QUANTITY,
        string $NOTES,
    ) {

        BuildAssembly::where('ID', $ID)
            ->update([
                'CODE' => $CODE,
                'QUANTITY' => $QUANTITY,
                'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : null,
                'UNIT_ID' => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'NOTES' => $NOTES
            ]);
    }
    public function Delete(int $ID)
    {
        BuildAssembly::where('ID', $ID)->delete();
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        BuildAssembly::where('ID', $ID)
            ->update([
                'STATUS' => $STATUS
            ]);
    }
    public function Search($search, int $LOCATION_ID, int $perPage)
    {
        $result = BuildAssembly::query()
            ->select([
                'build_assembly.ID',
                'build_assembly.CODE',
                'build_assembly.DATE',
                'build_assembly.AMOUNT',
                'build_assembly.NOTES',
                'l.NAME as LOCATION_NAME',
                's.DESCRIPTION as STATUS',
                'i.DESCRIPTION as ITEM_NAME'
            ])
            ->join('item as i', 'i.ID', '=', 'build_assembly.ASSEMBLY_ITEM_ID')
            ->join('document_status_map as s', 's.ID', '=', 'build_assembly.STATUS')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'build_assembly.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('build_assembly.CODE', 'like', '%' . $search . '%')
                    ->orWhere('build_assembly.AMOUNT', 'like', '%' . $search . '%')
                    ->orWhere('build_assembly.NOTES', 'like', '%' . $search . '%');
            })
            ->orderBy('build_assembly.ID', 'desc')
            ->paginate($perPage);

        return $result;
    }

    public function ComponentStore(int $BUILD_ASSEMBLY_ID, int $ITEM_ID, float $QUANTITY, float $AMOUNT, int $BATCH_ID, int $ASSET_ACCOUNT_ID)
    {
        $ID = (int) $this->object->ObjectNextID('BUILD_ASSEMBLY_ITEMS');

        BuildAssemblyItems::create([
            'ID' => $ID,
            'BUILD_ASSEMBLY_ID' => $BUILD_ASSEMBLY_ID,
            'ITEM_ID' => $ITEM_ID,
            'QUANTITY' => $QUANTITY,
            'AMOUNT' => $AMOUNT,
            'BATCH_ID' => $BATCH_ID > 0 ? $BATCH_ID : 0,
            'ASSET_ACCOUNT_ID' => $ASSET_ACCOUNT_ID > 0 ? $ASSET_ACCOUNT_ID : 0,
        ]);
    }
    public function ComponentUpdate(int $ID, int $BUILD_ASSEMBLY_ID, int $ITEM_ID, float $QUANTITY, float $AMOUNT)
    {
        BuildAssemblyItems::where('ID', $ID)
            ->where('BUILD_ASSEMBLY_ID', $BUILD_ASSEMBLY_ID)
            ->where('ITEM_ID', $ITEM_ID)->update([
                    'QUANTITY' => $QUANTITY,
                    'AMOUNT' => $AMOUNT
                ]);
    }
    public function ComponentDelete(int $ID)
    {
        BuildAssemblyItems::where('ID', $ID)->delete();
    }

    public function ComponentList(int $BUILD_ASSEMBLY_ID) {

        $result = BuildAssemblyItems::query()
        ->select(['build_assembly_items.ID',
        'build_assembly_items.QUANTITY',
        'build_assembly_items.AMOUNT',
        'build_assembly_items.BATCH_ID',
        'i.DESCRIPTION as ITEM_NAME',
        'build_assembly_items.ID'])
        ->join('item as i','i.ID','=','build_assembly_items.ITEM_ID')
        ->where('build_assembly_items.BUILD_ASSEMBLY_ID',$BUILD_ASSEMBLY_ID)
        ->get();
        // to be continue
    }

}