<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Events\Index;
use App\Livewire\Events\Create;
use App\Livewire\Events\Edit;
use App\Livewire\Events\Show;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Cache clearing route (optional)
Route::get('/cache', function () {
    \Artisan::call('config:clear');
    \Artisan::call('optimize:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    return '<h3>Caches have been cleared successfully!</h3>';
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

// Admin routes group
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
         Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        // Events routes
        Route::prefix('events')
            ->name('events.')
            ->group(function () {
                Route::get('/', Index::class)->name('index');
                Route::get('/create/{event?}', Create::class)->name('create');
                Route::get('/{event}', Show::class)->name('show');
            });

        // Roles routes
        Route::prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('/', \App\Livewire\Admin\Roles\Index::class)->name('index');
                Route::get('/create', \App\Livewire\Admin\Roles\Create::class)->name('create');
                Route::get('/{role}/edit', \App\Livewire\Admin\Roles\Edit::class)->name('edit');
            });

        // Permissions routes
        Route::prefix('permissions')
            ->name('permissions.')
            ->group(function () {
                Route::get('/', \App\Livewire\Admin\Permissions\Index::class)->name('index');
                Route::get('/create/{permission?}', \App\Livewire\Admin\Permissions\Create::class)->name('create');
            });

        // Users CRUD routes
        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', \App\Livewire\Admin\Users\Index::class)->name('index');
                Route::get('/create', \App\Livewire\Admin\Users\Create::class)->name('create');
                Route::get('/{user}', \App\Livewire\Admin\Users\Show::class)->name('show');
                Route::get('/{user}/edit', \App\Livewire\Admin\Users\Edit::class)->name('edit');
            });

        // ========== AUTO-LOGIN (IMPERSONATE) ROUTES ==========
        // Auto‑login (impersonate) route
        Route::get('/loginwith/{id}', function ($id) {
            $user = User::findOrFail($id);
            $currentUser = Auth::user();

            if (in_array($currentUser->role, [0, 1])) {
                if ($user->role == 1) {
                    session()->put('super_admin', $currentUser);
                } else {
                    session()->put('company_admin', $currentUser);
                }
                Auth::loginUsingId($user->id);
            }
            return redirect()->intended('/admin/dashboard');
        })->name('user.autoLogin');

        // Back to admin route
        Route::get('/backtoadmin', function () {
            if (request()->has('admin') && session()->has('super_admin')) {
                Auth::login(session('super_admin'));
                session()->forget(['super_admin', 'company_admin']);
            } elseif (request()->has('company') && session()->has('company_admin')) {
                Auth::login(session('company_admin'));
                session()->forget('company_admin');
            } else {
                return redirect()->route('login')->withErrors('No admin session available.');
            }
            return redirect()->intended('/admin/dashboard');
        })->name('backtoadmin');
    });

// ========== LOGOUT ROUTES ==========
// Standard logout (POST) – recommended for security
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Optional: GET logout for convenience (use with caution)
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout.get');

// Special logout for impersonation – returns to original admin account
Route::get('/logout-and-back', function () {
    if (session()->has('super_admin')) {
        Auth::login(session('super_admin'));
        session()->forget(['super_admin', 'company_admin']);
    } elseif (session()->has('company_admin')) {
        Auth::login(session('company_admin'));
        session()->forget('company_admin');
    } else {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
    return redirect()->route('admin.dashboard');
})->name('logout.impersonate');
