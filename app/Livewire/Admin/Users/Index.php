<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Livewire\Attributes\Layout;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    public $showDeleteModal = false;
    public $userIdToDelete = null;

    public function mount()
    {
        $this->authorize('user-list');
    }

    public function render()
    {
        return view('livewire.admin.users.index', [
            'users' => User::paginate(10),
        ]);
    }

    public function confirmDelete($userId)
    {
        if ($userId == auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }
        $this->userIdToDelete = $userId;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        $this->authorize('user-delete');

        if ($this->userIdToDelete) {
            if ($this->userIdToDelete == auth()->id()) {
                session()->flash('error', 'You cannot delete your own account.');
                $this->showDeleteModal = false;
                $this->userIdToDelete = null;
                return;
            }

            $user = User::find($this->userIdToDelete);
            if ($user) {
                $user->delete();
                session()->flash('message', 'User deleted successfully.');
            } else {
                session()->flash('error', 'User not found.');
            }
        }

        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
    }
}
