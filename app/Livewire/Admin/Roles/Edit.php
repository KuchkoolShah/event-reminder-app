<?php

namespace App\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Edit extends Component
{
    use AuthorizesRequests;

    public Role $role;
    public $name;
    public $selectedPermissions = [];
    public $permissions;
    public $allSelected = false;

    public function mount(Role $role)
    {
        $this->authorize('role-edit');  // ensure this permission exists

        $this->role = $role;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->permissions = Permission::select('id', 'name')->get(); // only needed columns

        $this->updateAllSelectedState();
    }

    public function updatedSelectedPermissions()
    {
        $this->updateAllSelectedState();
    }

    protected function updateAllSelectedState()
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
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
            'selectedPermissions' => 'array|exists:permissions,id',
        ];
    }

    public function update()
    {
        $this->validate();

        // Protect system roles from name change (optional)
        $systemRoles = ['super-admin', 'admin'];
        if (in_array($this->role->name, $systemRoles) && $this->role->name !== $this->name) {
            session()->flash('error', 'System role names cannot be changed.');
            return;
        }

        $this->role->update(['name' => $this->name]);
        $this->role->permissions()->sync($this->selectedPermissions);

        session()->flash('message', "Role '{$this->name}' updated successfully.");
        return $this->redirectRoute('admin.roles.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.edit');
    }
}
