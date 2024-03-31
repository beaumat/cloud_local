<?php

namespace App\Services;

use App\Models\HemodialysisMachines;

class HemodialysisMachineServices
{
    private $object;
    private $user;
    public function __construct(ObjectServices $objectService, UserServices $userServices)
    {
        $this->object = $objectService;
        $this->user = $userServices;
    }

    public function get(int $ID)
    {
        return HemodialysisMachines::where('ID', $ID)->first();
    }
    public function Store(string $CODE, int $TYPE, string $DESCRIPTION, int $LOCATION_ID): int
    {
        $ID = $this->object->ObjectNextID('HEMODIALYSIS_MACHINE');

        HemodialysisMachines::create([
            'ID' => $ID,
            'CODE' => $CODE,
            'TYPE' => $TYPE,
            'DESCRIPTION' => $DESCRIPTION,
            'LOCATION_ID' => $LOCATION_ID
        ]);

        return $ID;
    }
    public function Update(int $ID, string $CODE, int $TYPE, string $DESCRIPTION, int $LOCATION_ID)
    {

        HemodialysisMachines::where('ID', $ID)->update([
            'CODE' => $CODE,
            'TYPE' => $TYPE,
            'DESCRIPTION' => $DESCRIPTION,
            'LOCATION_ID' => $LOCATION_ID
        ]);
    }
    public function Delete(int $ID)
    {
        HemodialysisMachines::where('ID', $ID)->delete();
    }
    public function Search($search)
    {
        return HemodialysisMachines::query()
            ->select([
                'hemodialysis_machine.ID',
                'hemodialysis_machine.CODE',
                'hemodialysis_machine.DESCRIPTION',
                'machine_type.DESCRIPTION as TYPE',
                'location.NAME as LOCATION'
            ])
            ->join('machine_type', 'machine_type.ID', '=', 'hemodialysis_machine.TYPE')
            ->join('location', 'location.ID', '=', 'hemodialysis_machine.LOCATION_ID')
            ->where('hemodialysis_machine.CODE', 'like', '%' . $search . '%')
            ->orWhere('hemodialysis_machine.DESCRIPTION', 'like', '%' . $search . '%')
            ->get();

    }


}