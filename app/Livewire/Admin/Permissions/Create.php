<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Create extends Component
{
    use AuthorizesRequests;

    public ?Permission $permission = null;
    public $name = '';
    public $permission_id = null;
    public $isEditing = false;


    public function __construct()
    {

    }

    public function mount(Permission $permission = null)
    {
        if ($permission && $permission->exists) {
           
            $this->authorize('permission-edit');
            $this->permission = $permission;
            $this->name = $permission->name;
            $this->permission_id = $permission->id;
            $this->isEditing = true;
        } else {
            // Creating a new permission
            $this->authorize('permission-create');
            $this->permission = null;
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
        $this->validate();

        if ($this->isEditing) {
            $this->permission->update(['name' => $this->name]);
            session()->flash('message', 'Permission updated successfully.');
        } else {
            Permission::create(['name' => $this->name]);
            session()->flash('message', 'Permission created successfully.');
        }

        return $this->redirectRoute('admin.permissions.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.permissions.create');
    }
}
