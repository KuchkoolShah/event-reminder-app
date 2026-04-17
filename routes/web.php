<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Permissions\Create as PermissionCreate;
use App\Livewire\Admin\Permissions\Index as PermissionsIndex;
use App\Livewire\Admin\Roles\Create as RoleCreate;
use App\Livewire\Admin\Roles\Edit as RoleEdit;
use App\Livewire\Admin\Roles\Index as RolesIndex;
use App\Livewire\Admin\Users\Create as UserCreate;
use App\Livewire\Admin\Users\Edit as UserEdit;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Show as UserShow;
use App\Livewire\Events\Create as EventCreate;
use App\Livewire\Events\Index as EventsIndex;
use App\Livewire\Events\Show as EventShow;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Development Cache Clearing (Local only)
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/cache', function () {
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        return '<h3>Caches cleared!</h3>';
    })->withoutMiddleware(['auth']);
}

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes (auth + admin middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // Events
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', EventsIndex::class)->name('index');
            Route::get('/create/{event?}', EventCreate::class)->name('create');
            Route::get('/{event:slug}', EventShow::class)->name('show'); // slug binding
        });

        // Roles management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', RolesIndex::class)->name('index');
            Route::get('/create', RoleCreate::class)->name('create');
            Route::get('/{role}/edit', RoleEdit::class)->name('edit');
        });

        // Permissions management
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', PermissionsIndex::class)->name('index');
            Route::get('/create/{permission?}', PermissionCreate::class)->name('create');
        });

        // Users management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', UsersIndex::class)->name('index');
            Route::get('/create', UserCreate::class)->name('create');
            Route::get('/{user}', UserShow::class)->name('show');
            Route::get('/{user}/edit', UserEdit::class)->name('edit');
        });

        // Impersonation
        Route::get('/login-as/{user}', function (User $user) {
            abort_unless(auth()->user()?->hasRole('admin'), 403);
            session()->put('impersonate', auth()->id());
            auth()->login($user);

            return redirect()->route('admin.dashboard');
        })->name('impersonate');

        Route::get('/stop-impersonating', function () {
            if (!session()->has('impersonate')) {
                return redirect()->route('admin.dashboard');
            }

            auth()->loginUsingId(session('impersonate'));
            session()->forget('impersonate');

            return redirect()->route('admin.dashboard');
        })->name('stop.impersonate');
    });
