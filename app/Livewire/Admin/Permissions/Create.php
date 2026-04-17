<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
#[Layout('layouts.app')]
class Create extends Component
{
    use AuthorizesRequests;

    public ?Permission $permission = null;
    public $name = '';
    public $isEditing = false;

    public function mount(Permission $permission = null)
    {
        if ($permission && $permission->exists) {
            $this->permission = $permission;
            $this->name = $permission->name;
            $this->isEditing = true;
            // DO NOT authorize here
        } else {
            $this->isEditing = false;
        }
    }

    protected function rules()
    {
        $uniqueRule = $this->isEditing
            ? 'unique:permissions,name,' . $this->permission->id
            : 'unique:permissions,name';

        return [
            'name' => 'required|string|max:255|' . $uniqueRule,
        ];
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->authorize('permission-edit');
        } else {
            $this->authorize('permission-create');
        }

        $this->validate();

        if ($this->isEditing) {
            $this->permission->update(['name' => $this->name]);
            session()->flash('message', 'Permission updated successfully.');
        } else {
            Permission::create(['name' => $this->name, 'guard_name' => 'web']);
            session()->flash('message', 'Permission created successfully.');
        }

        return $this->redirectRoute('admin.permissions.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.permissions.create');
    }
}
