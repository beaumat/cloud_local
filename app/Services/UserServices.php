<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserServices
{
    public function Store(string $Username, string $Password, int $Contact_id, bool $Inactive): int
    {

        $user =  User::create([
            'name' => $Username,
            'email' => null,
            'email_verified_at' => now(),
            'password' => Hash::make($Password),
            'remember_token' => Str::random(10),
            'contact_id' => $Contact_id ? $Contact_id :  null,
            'inactive' => $Inactive,
        ]);

        return $user->id;

        //  $user->assignRole('superadmin','superadmin');

    }

    public function Update(int $id, string $Username, string $Password, int $Contact_id, bool $Inactive): void
    {
        if ($Password) {
            User::where('id', $id)->update([
                'name' => $Username,
                'password' => Hash::make($Password),
                'contact_id' => $Contact_id ? $Contact_id :  null,
                'inactive' => $Inactive,
            ]);
        }

        User::where('id', $id)->update([
            'name' => $Username,
            'contact_id' => $Contact_id ? $Contact_id :  null,
            'inactive' => $Inactive,
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
                        'users.inactive'
                    ]
                )
                ->leftJoin('contact', 'contact.id', '=', 'users.contact_id')
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
