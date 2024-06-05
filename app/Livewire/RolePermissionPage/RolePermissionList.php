<?php

namespace App\Livewire\RolePermissionPage;

use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Title('Roles & Permission')]
class RolePermissionList extends Component
{
    public $roles = [];
    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.role-permission-page.role-permission-list');
    }
}
