<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $roleIdToDelete = null;
    public $assignedUsersCount = 0;
    public $assignedUsersList = [];
    public $hasUsersAssigned = false;

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $this->authorize('role-list');
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
        $role = Role::with('users')->findOrFail($id);
        $this->roleIdToDelete = $id;

        // Check if role has any users assigned
        $this->hasUsersAssigned = $role->users->count() > 0;

        // Get users assigned to this role
        $this->assignedUsersCount = $role->users->count();
        $this->assignedUsersList = $role->users->take(5)->pluck('name')->toArray();

        $this->showDeleteModal = true;
    }

    public function deleteRole()
    {
        $this->authorize('role-delete');

        $role = Role::with('users')->findOrFail($this->roleIdToDelete);

        // Check if role has any users assigned - CANNOT delete if users are assigned
        if ($role->users->count() > 0) {
            $userCount = $role->users->count();
            session()->flash('error', "Cannot delete role '{$role->name}'. It is currently assigned to {$userCount} user(s). Please reassign or remove these users from the role first.");
            $this->showDeleteModal = false;
            $this->reset(['roleIdToDelete', 'assignedUsersCount', 'assignedUsersList', 'hasUsersAssigned']);
            return;
        }

        // Delete the role (this will automatically remove all permission associations)
        $role->delete();

        session()->flash('message', "Role '{$role->name}' deleted successfully.");
        $this->showDeleteModal = false;
        $this->reset(['roleIdToDelete', 'assignedUsersCount', 'assignedUsersList', 'hasUsersAssigned']);
    }

    public function render()
    {
        $roles = Role::with('permissions', 'users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
        ]);
    }
}
