<?php
namespace App\Services;

use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\FundTransferReverse;

class FundTransferReverseServices
{
    public int $object_type_id = LogEntity::FUND_TRANSFER_REVERSE->value;
    private $objectServices;
    private $dateServices;
    private $systemSettingServices;
    private $usersLogServices;
    private $userServices;
    public function __construct(ObjectServices $objectServices, DateServices $dateServices, SystemSettingServices $systemSettingServices, UsersLogServices $usersLogServices, UserServices $userServices)
    {
        $this->objectServices        = $objectServices;
        $this->dateServices          = $dateServices;
        $this->systemSettingServices = $systemSettingServices;
        $this->usersLogServices      = $usersLogServices;
        $this->userServices          = $userServices;

    }
    public function Get(int $ID)
    {
        $result = FundTransferReverse::where('ID', '=', $ID)->first();

        return $result;
    }
    public function Store($DATE, string $CODE, string $NOTES, int $FUND_TRANSFER_ID, int $LOCATION_ID): int
    {

        $ID          = (int) $this->objectServices->ObjectNextID('FUND_TRANSFER');
        $OBJECT_TYPE = (int) $this->objectServices->ObjectTypeID('FUND_TRANSFER');
        $isLocRef    = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        FundTransferReverse::create([
            'ID'               => $ID,
            'RECORDED_ON'      => $this->dateServices->Now(),
            'DATE'             => $DATE,
            'CODE'             => $CODE !== '' ? $CODE : $this->objectServices->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'NOTES'            => $NOTES,
            'FUND_TRANSFER_ID' => $FUND_TRANSFER_ID,
            'USERNAME'         => $this->userServices->GetUsername(),
            'LOCATION_ID'      => $LOCATION_ID,

        ]);

        $this->usersLogServices->AddLogs(TransType::INSERT, LogEntity::FUND_TRANSFER_REVERSE, $ID);
        
        return $ID;
    }

}
