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

    public $search = '';
    public $perPage = 10;
    public $showDeleteModal = false;
    public $userIdToDelete = null;

    protected $queryString = ['search', 'perPage'];

    public function mount()
    {
        $this->authorize('user-list');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
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

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id', 'asc')
            ->paginate($this->perPage);

        return view('livewire.admin.users.index', [
            'users' => $users,
        ]);
    }
}
