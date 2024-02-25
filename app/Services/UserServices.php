<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    private $systemSetting;
    public function __construct(SystemSettingServices $systemSettingServices)
    {
        $this->systemSetting = $systemSettingServices;
    }
    public function getLocationDefault(): int
    {
        if (intval(Auth::user()->name) === "superadmin") {

            return intval(Auth::user()->location_id) > 0 ? (int) Auth::user()->location_id : 0;
        }
     
        return intval(Auth::user()->location_id) > 0 ? (int) Auth::user()->location_id : $this->systemSetting->GetValue('DefaultLocationId');

    }
    public function Store(string $Username, string $Password, int $Contact_id, bool $Inactive, int $Location_id): int
    {

        $user = User::create([
            'name' => $Username,
            'email' => null,
            'email_verified_at' => now(),
            'password' => Hash::make($Password),
            'remember_token' => Str::random(10),
            'contact_id' => $Contact_id ? $Contact_id : null,
            'inactive' => $Inactive,
            'location_id' => $Location_id > 0 ? $Location_id : 0
        ]);

        return $user->id;

        //  $user->assignRole('superadmin','superadmin');

    }

    public function Update(int $id, string $Username, string $Password, int $Contact_id, bool $Inactive, int $Location_id): void
    {
        if ($Password) {
            User::where('id', $id)->update([
                'name' => $Username,
                'password' => Hash::make($Password),
                'contact_id' => $Contact_id ? $Contact_id : null,
                'inactive' => $Inactive,
                'location_id' => $Location_id > 0 ? $Location_id : 0
            ]);

            return;
        }

        User::where('id', $id)->update([
            'name' => $Username,
            'contact_id' => $Contact_id ? $Contact_id : null,
            'inactive' => $Inactive,
            'location_id' => $Location_id > 0 ? $Location_id : 0
        ]);
    }

    public function Delete(int $id): void
    {
        User::where('id', $id)->delete();
    }

    public function Search($search)
    {
        if (!$search) {
            return User::query()
                ->select(
                    [
                        'users.id',
                        'users.name',
                        'contact.name as employee',
                        'users.inactive',
                        'l.NAME as location'
                    ]
                )
                ->leftJoin('contact', 'contact.id', '=', 'users.contact_id')
                ->leftJoin('location as l', 'l.ID', '=', 'users.location_id')
                ->orderBy('users.id', 'asc')
                ->get();
        } else {
            return User::query()
                ->select(
                    [
                        'users.name',
                        'contact.name as employee',
                        'users.inactive'
                    ]
                )
                ->leftJoin('contact', 'contact.id', '=', 'users.contact_id')
                ->where('users.name', 'like', '%' . $search . '%')
                ->orderBy('users.id', 'asc')
                ->get();
        }
    }
}
