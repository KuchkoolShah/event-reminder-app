<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

#[Layout('layouts.app')]
class Create extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $roles = [];

    // UI helpers
    public $showPassword = false;
    public $showPasswordConfirmation = false;

     public function mount()
    {
        $this->authorize('user-create');
    }
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => 'array|exists:roles,id', // IDs must exist in roles table
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'roles' => 'roles',
        ];
    }

    public function updatedEmail()
    {
        $this->validateOnly('email');
    }

    public function updatedName()
    {
        $this->validateOnly('name');
    }

    public function generatePassword()
    {
        $this->password = Str::password(12, true, true, false, false);
        $this->password_confirmation = $this->password;
        $this->dispatch('password-generated');
    }

    public function save()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // ✅ Fix: Convert role IDs to role names before syncing
        if (!empty($this->roles)) {
            $roleNames = Role::whereIn('id', $this->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }

        session()->flash('message', '✅ User created successfully.');
        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        $allRoles = Role::all();
        return view('livewire.admin.users.create', compact('allRoles'));
    }
}
