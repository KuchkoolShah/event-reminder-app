<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Edit extends Component
{
    use AuthorizesRequests;

    public User $user;
    public $name;
    public $email;
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];

    public function mount(User $user)
    {
        $this->authorize('user-edit');

        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->roles = $user->roles->pluck('id')->toArray();
    }

    public function generatePassword()
    {
        $this->password = Str::password(12, true, true, false, false);
        $this->password_confirmation = $this->password;
        $this->dispatch('password-generated');
    }

    public function render()
    {
        return view('livewire.admin.users.edit', [
            'allRoles' => Role::all(),
        ]);
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => 'array|exists:roles,id',
        ];
    }

    public function update()
    {
        $this->authorize('user-edit'); // Extra security

        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $this->user->update($data);
        if (auth()->user()->can('role-assign')) {
            if (!empty($this->roles)) {
                $roleNames = Role::whereIn('id', $this->roles)->pluck('name')->toArray();
                $this->user->syncRoles($roleNames);
            } else {
                $this->user->syncRoles([]);
            }
        }

        session()->flash('message', 'User updated successfully.');
        return redirect()->route('admin.users.index');
    }
}
