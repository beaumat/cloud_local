<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    private $systemSetting;
    private $dateServices;
    public function __construct(SystemSettingServices $systemSettingServices, DateServices $dateServices)
    {
        $this->systemSetting = $systemSettingServices;
        $this->dateServices = $dateServices;
    }
    public function getTransactionDateDefault(): string
    {
        if (Auth::user()->trans_date == null) {
            return  $this->dateServices->NowDate();
        }

        return Auth::user()->trans_date;
    }
    public function getLocationDefault(): int
    {
        if (intval(Auth::user()->name) === "superadmin") {

            return intval(Auth::user()->location_id) > 0 ? (int) Auth::user()->location_id : 0;
        }

        $locId = Auth::user()->location_id;

        return intval($locId) > 0 ? (int)$locId : $this->systemSetting->GetValue('DefaultLocationId');
    }
    public function UserId(): int
    {
        return (int) Auth::user()->id;
    }
    public function Store(string $Username, string $Password, int $Contact_id, bool $Inactive, int $Location_id, string $trans_date, bool $locked_location): int
    {

        $user = User::create([
            'name'              => $Username,
            'email'             => null,
            'email_verified_at' => now(),
            'password'          => Hash::make($Password),
            'remember_token'    => Str::random(10),
            'contact_id'        => $Contact_id ? $Contact_id : null,
            'inactive'          => $Inactive,
            'location_id'       => $Location_id > 0 ? $Location_id : 0,
            'trans_date'        => $trans_date == '' ? null : $trans_date,
            'locked_location'   => $locked_location
        ]);

        return $user->id;

        //  $user->assignRole('superadmin','superadmin');

    }

    public function Update(int $id, string $Username, string $Password, int $Contact_id, bool $Inactive, int $Location_id, string $trans_date, bool $locked_location): void
    {
        if ($Password) {
            User::where('id', $id)->update([
                'name'              => $Username,
                'password'          => Hash::make($Password),
                'contact_id'        => $Contact_id ? $Contact_id : null,
                'inactive'          => $Inactive,
                'location_id'       => $Location_id > 0 ? $Location_id : 0,
                'trans_date'        => $trans_date == '' ? null : $trans_date,
                'locked_location'   => $locked_location
            ]);

            return;
        }

        User::where('id', $id)->update([
            'name'              => $Username,
            'contact_id'        => $Contact_id ? $Contact_id : null,
            'inactive'          => $Inactive,
            'location_id'       => $Location_id > 0 ? $Location_id : 0,
            'trans_date'        => $trans_date == '' ? null : $trans_date,
            'locked_location'   => $locked_location
        ]);
    }

    public function Delete(int $id): void
    {
        User::where('id', $id)->delete();
    }

    public function Search($search)
    {

        $result = User::query()
            ->select(
                [
                    'users.id',
                    'users.name',
                    'contact.name as employee',
                    'users.inactive',
                    'l.NAME as location',
                    'users.trans_date',
                    'users.locked_location as locked'
                ]
            )
            ->leftJoin('contact', 'contact.id', '=', 'users.contact_id')
            ->leftJoin('location as l', 'l.ID', '=', 'users.location_id')
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->orWhere('users.name', 'like', '%' . $search . '%');
                    $q->orWhere('contact.NAME', 'like', '%' . $search . '%');
                    $q->orWhere('l.NAME', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('users.id', 'asc')
            ->get();

        return   $result;
    }
}
