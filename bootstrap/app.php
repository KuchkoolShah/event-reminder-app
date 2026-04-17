<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

// Middleware aliases
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'              => AdminMiddleware::class,
            'role'               => RoleMiddleware::class,
            'permission'         => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling (if needed)
        // Example: $exceptions->render(...);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('reminders:dispatch')->everyMinute();
    })
    ->create();
