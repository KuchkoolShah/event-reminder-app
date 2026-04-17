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
        $this->permissionIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function deletePermission()
    {
        $this->authorize('permission-delete');

        $permission = Permission::findOrFail($this->permissionIdToDelete);

        // Protect system permissions
        $protected = ['manage permissions', 'manage roles', 'access admin'];
        if (in_array($permission->name, $protected)) {
            session()->flash('error', 'System permissions cannot be deleted.');
            $this->showDeleteModal = false;
            return;
        }

        $permission->delete();
        session()->flash('message', 'Permission deleted successfully.');
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $permissions = Permission::query()
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
