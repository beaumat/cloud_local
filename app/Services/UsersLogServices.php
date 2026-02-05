<?php
namespace App\Services;

use App\Enums\DocStatus;
use App\Enums\LogEntity;
use App\Enums\TransType;
use App\Models\UsersLog;

class UsersLogServices
{
    public $userServices;
    public $dateServices;
    public function __construct(UserServices $userServices, DateServices $dateServices)
    {
        $this->userServices = $userServices;
        $this->dateServices = $dateServices;
    }
    public function AddLogs(TransType $TRANS_TYPE, LogEntity $LOG_TYPE, int $LOG_ID)
    {
        try {

            $USERNAME = $this->userServices->GetUsername();
            if ($USERNAME !== "") { // USERNAME MUST BE REQUIRED
                UsersLog::create([
                    'USERNAME'     => $USERNAME,
                    'TRANS_TYPE'   => $TRANS_TYPE,
                    'LOG_DATETIME' => $this->dateServices->NowDateTime(),
                    'LOG_TYPE'     => $LOG_TYPE,
                    'LOG_ID'       => $LOG_ID,
                ]);
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function StatusLog(int $STATUS, LogEntity $LOG_TYPE, int $LOG_ID)
    {
        switch ($STATUS) {
            case DocStatus::POSTED:
                $this->AddLogs(TransType::POST, $LOG_TYPE, $LOG_ID);
                break;

            case DocStatus::UNPOSTED:
                $this->AddLogs(TransType::UNPOST, $LOG_TYPE, $LOG_ID);
                break;
        }
    }

}
