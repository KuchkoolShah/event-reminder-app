<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests;

    public $roles;
    public $showDeleteModal = false;
    public $roleIdToDelete = null;

    public function __construct()
    {
        // Authorize listing roles
        $this->authorize('role-list');
    }

    public function mount()
    {
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions')->get();
    }

    public function confirmDelete($id)
    {
        $this->roleIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteRole()
    {
        // Authorize delete action
        $this->authorize('role-delete');

        $role = Role::findOrFail($this->roleIdToDelete);

        if (in_array($role->name, ['admin', 'super_admin'])) {
            session()->flash('error', 'System roles cannot be deleted.');
            $this->showDeleteModal = false;
            return;
        }

        $role->delete();
        session()->flash('message', 'Role deleted successfully.');
        $this->showDeleteModal = false;
        $this->loadRoles();
    }

    public function render()
    {
        return view('livewire.admin.roles.index');
    }
}
