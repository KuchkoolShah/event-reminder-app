<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $permissionIdToDelete = null;
    public $assignedRoles = []; // Store roles that have this permission

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $this->authorize('permission-list');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $permission = Permission::with('roles')->findOrFail($id);
        $this->permissionIdToDelete = $id;

        // Get assigned roles
        $this->assignedRoles = $permission->roles->pluck('name')->toArray();

        $this->showDeleteModal = true;
    }

    public function deletePermission()
    {
        $this->authorize('permission-delete');

        $permission = Permission::with('roles')->findOrFail($this->permissionIdToDelete);

        // Protect system permissions
        $protected = ['manage permissions', 'manage roles', 'access admin'];
        if (in_array($permission->name, $protected)) {
            session()->flash('error', 'System permissions cannot be deleted.');
            $this->showDeleteModal = false;
            $this->reset(['permissionIdToDelete', 'assignedRoles']);
            return;
        }

        // Check if permission is assigned to any roles
        if ($permission->roles->isNotEmpty()) {
            $roleNames = $permission->roles->pluck('name')->join(', ');
            session()->flash('error', "Cannot delete permission '{$permission->name}'. It is currently assigned to these roles: {$roleNames}. Please remove the permission from these roles first.");
            $this->showDeleteModal = false;
            $this->reset(['permissionIdToDelete', 'assignedRoles']);
            return;
        }

        $permission->delete();
        session()->flash('message', "Permission '{$permission->name}' deleted successfully.");
        $this->showDeleteModal = false;
        $this->reset(['permissionIdToDelete', 'assignedRoles']);
    }

    public function render()
    {
        $permissions = Permission::query()
            ->with('roles') // Eager load roles to show in table
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('guard_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.permissions.index', [
            'permissions' => $permissions,
        ]);
    }
}
