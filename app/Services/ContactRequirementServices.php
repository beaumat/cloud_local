<?php

namespace App\Services;

use App\Models\ContactRequirements;
use App\Models\Requirements;
use Illuminate\Support\Carbon;

class ContactRequirementServices
{

    private $object;
    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function AutoCreateList(int $CONTACT_ID)
    {
        $data = Requirements::where('INACTIVE', 0)->get();
        foreach ($data as $list) {
            $this->Store($CONTACT_ID, $list->ID);
        }
    }
    public function Store(int $CONTACT_ID, int $REQUIREMENT_ID)
    {
        $ID = $this->object->ObjectNextID('CONTACT_REQUIREMENT');
        ContactRequirements::create([
            'ID' => $ID,
            'CONTACT_ID' => $CONTACT_ID,
            'REQUIREMENT_ID' => $REQUIREMENT_ID
        ]);
    }
    public function UpdateIsComplete(int $ID, bool $VALUE)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'IS_COMPLETE' => $VALUE,
                'DATE_COMPLETED' => $VALUE ? Carbon::now()->format('Y-m-d') : null
            ]);
    }
    public function UpdateNotApplicable(int $ID, bool $VALUE)
    {
        ContactRequirements::where('ID', $ID)
            ->update([
                'NOT_APPLICABLE' => $VALUE
            ]);
    }
    public function GetCountRequirement(int $CONTACT_ID): int
    {
        return (int) ContactRequirements::where('CONTACT_ID', $CONTACT_ID)->where('IS_COMPLETE', 0)->where('NOT_APPLICABLE',0)->count();
    }
    public function GetList(int $CONTACT_ID)
    {
        return ContactRequirements::query()
            ->select([
                'contact_requirement.ID',
                'r.DESCRIPTION',
                'contact_requirement.REQUIREMENT_ID',
                'contact_requirement.IS_COMPLETE',
                'contact_requirement.NOT_APPLICABLE'

            ])
            ->leftJoin('requirement as r', 'r.ID', '=', 'contact_requirement.REQUIREMENT_ID')
            ->where('contact_requirement.CONTACT_ID', $CONTACT_ID)
            ->get();
    }
}