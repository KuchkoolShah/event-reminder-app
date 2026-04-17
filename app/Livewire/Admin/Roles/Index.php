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
        $this->roleIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deleteRole()
    {
        $this->authorize('role-delete');

        $role = Role::findOrFail($this->roleIdToDelete);

        // Protect system roles
        $protectedRoles = ['admin', 'super_admin'];
        if (in_array($role->name, $protectedRoles)) {
            session()->flash('error', 'System roles cannot be deleted.');
            $this->showDeleteModal = false;
            return;
        }

        $role->delete();
        session()->flash('message', 'Role deleted successfully.');
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $roles = Role::with('permissions')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.roles.index', [
            'roles' => $roles,
        ]);
    }
}
