<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Create extends Component
{
    use AuthorizesRequests;

    public $name = '';
    public $selectedPermissions = [];
    public $permissions;
    public $allSelected = false;

    public function mount()
    {
        $this->authorize('role-create'); // Use a consistent permission name

        // Load only necessary columns for better performance
        $this->permissions = Permission::select('id', 'name')->get();

        // If there are no permissions, disable select-all functionality
        if ($this->permissions->isEmpty()) {
            $this->allSelected = false;
        }
    }

    public function updatedSelectedPermissions()
    {
        $this->allSelected = !empty($this->permissions) &&
            count($this->selectedPermissions) === $this->permissions->count();
    }

    public function toggleSelectAll()
    {
        if ($this->allSelected) {
            $this->selectedPermissions = [];
        } else {
            $this->selectedPermissions = $this->permissions->pluck('id')->toArray();
        }
        // The updatedSelectedPermissions() will automatically set $allSelected
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'array|exists:permissions,id',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'role name',
            'selectedPermissions' => 'permissions',
        ];
    }

    public function save()
    {
        $this->validate();

        $role = Role::create(['name' => $this->name]);

        if (!empty($this->selectedPermissions)) {
            $role->permissions()->sync($this->selectedPermissions);
        }

        session()->flash('message', "Role '{$this->name}' created successfully.");
        return $this->redirectRoute('admin.roles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.create');
    }
}
