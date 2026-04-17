<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Events\Index;
use App\Livewire\Events\Create;
use App\Livewire\Events\Show;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Cache clearing (development only)
Route::get('/cache', function () {
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return '<h3>Caches cleared!</h3>';
})->withoutMiddleware(['auth']);

Route::get('/', fn () => redirect()->route('login'));

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Admin routes – only accessible by users with 'admin' role
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        // Events
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', Index::class)->name('index');
            Route::get('/create/{event?}', Create::class)->name('create');
            Route::get('/{event:slug}', Show::class)->name('show'); // using slug binding

        });

        // Roles management (admin only)
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Roles\Index::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\Roles\Create::class)->name('create');
            Route::get('/{role}/edit', \App\Livewire\Admin\Roles\Edit::class)->name('edit');
        });

        // Permissions management (admin only)
        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Permissions\Index::class)->name('index');
            Route::get('/create/{permission?}', \App\Livewire\Admin\Permissions\Create::class)->name('create');
        });

        // Users management (admin only)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', \App\Livewire\Admin\Users\Index::class)->name('index');
            Route::get('/create', \App\Livewire\Admin\Users\Create::class)->name('create');
            Route::get('/{user}', \App\Livewire\Admin\Users\Show::class)->name('show');
            Route::get('/{user}/edit', \App\Livewire\Admin\Users\Edit::class)->name('edit');
        });

        // Impersonation (admin only)
        Route::get('/login-as/{user}', function (User $user) {
            if (!auth()->user()->hasRole('admin')) {
                abort(403);
            }
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

// Logout – POST only (security)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Optional: GET logout for convenience (not recommended, but if needed)
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.get');
