<?php

namespace App\Livewire\RolePermissionPage;

use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionConfig extends Component
{
    public int $role_id;
    public $role;
    public string $role_name;
    public $id;
    public $assignedPermissions = [];
    public $allPermissions;
    public $searchSign;
    public $searchUnsign;
    public $unassignedPermissions = [];

    public function updatedsearchUnsign()
    {
        $this->assignedList();
    }
    public function updatesearchSign()
    {
        $this->unassignedList();
    }
    public function mount($id)
    {
        $this->role = Role::findById($id);
        if ($this->role) {
            $this->allPermissions = Permission::all();
            $this->id = $id;
            $this->role_name = $this->role->name;
            $this->reloadPermession();
            return;
        }

        $errorMessage = 'Error occurred: Record not found. ';
        return Redirect::route('maintenancesettingsroles')->with('error', $errorMessage);
    }
    public function reloadPermession()
    {
        $this->assignedList();
        $this->unassignedList();
    }

    public function assignedList()
    {
        $this->assignedPermissions = $this->role->permissions;
    }
    public function unassignedList()
    {
        $this->unassignedPermissions = $this->allPermissions->diff($this->assignedPermissions);
    }

    public function addPermission($Name)
    {

        $permission = Permission::findByName($Name);
        if ($this->role && $permission) {
            $this->role->givePermissionTo($permission);
            $this->reloadPermession();
        }
    }
    public function deletePermission($Name)
    {
        $permission = Permission::findByName($Name);
        if ($this->role && $permission) {
            // Remove the permission from the role
            $this->role->revokePermissionTo($permission);
        }

        $this->reloadPermession();
    }
    public function render()
    {
        return view('livewire.role-permission-page.role-permission-config');
    }
}
