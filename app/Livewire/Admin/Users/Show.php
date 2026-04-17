<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Show extends Component
{
    use AuthorizesRequests;

    public User $user;

    public function mount(User $user)
    {
        // Authorize view access – using 'user-list' (consistent with index)
        $this->authorize('user-list');

        $this->user = $user->load('roles');
    }

    public function render()
    {
        return view('livewire.admin.users.show');
    }
}
