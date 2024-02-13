<?php

namespace App\Services;

use App\Models\SystemSetting;

class SystemSettingServices
{
    public function SetValue( string $NAME, string $VALUE)
    {
            return SystemSetting::where('NAME',$NAME)->update(['VALUE' => $VALUE]);
    }

    public function GetValue(string $NAME): string
    {
        return (string) SystemSetting::where('NAME', $NAME)->first()->VALUE;
    }
     
}