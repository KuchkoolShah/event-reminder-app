<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;

    public $permissions;
    public $showDeleteModal = false;
    public $permissionIdToDelete = null;

    public function mount()
    {
        $this->authorize('permission-list');
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::all();
    }

    public function confirmDelete($id)
    {
        $this->permissionIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deletePermission()
    {
        $this->authorize('permission-delete');

        $permission = Permission::findOrFail($this->permissionIdToDelete);

        if (in_array($permission->name, ['manage permissions', 'manage roles', 'access admin'])) {
            session()->flash('error', 'System permissions cannot be deleted.');
            $this->showDeleteModal = false;
            return;
        }

        $permission->delete();
        session()->flash('message', 'Permission deleted successfully.');
        $this->showDeleteModal = false;
        $this->loadPermissions();
    }

    public function render()
    {
        return view('livewire.admin.permissions.index');
    }
}
