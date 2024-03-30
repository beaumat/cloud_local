<?php

namespace App\Services;

use App\Models\SystemSetting;

class SystemSettingServices
{
    public function GetList()
    {
        return \DB::table('system_settings')->select(['NAME', 'VALUE'])->get();
  
    }
    public function SetValue(string $NAME, string $VALUE)
    {
        return SystemSetting::where('NAME', $NAME)->update(['VALUE' => $VALUE]);
    }
    public function NewValue(string $NAME)
    {
        SystemSetting::create([
            'NAME' => $NAME,
            'VALUE' => ''
        ]);
    }
    public function GetValue(string $NAME): string
    {
        $result = SystemSetting::query()->select('VALUE')->where('NAME', $NAME)->limit(1);

        if ($result) {
            return $result->first()->VALUE ?? '';
        }
        return '';
    }

}