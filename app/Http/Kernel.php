<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // ... existing middleware ...
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // ... web middleware group ...
        ],

        'api' => [
            // ... api middleware group ...
        ],
    ];

    /**
     * The application's route middleware aliases.
     *
     * Aliases may be used instead of class names to assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        // ... existing aliases ...
        'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        'checkrole' => \App\Http\Middleware\CheckRole::class,
    ];

    /**
     * Register the application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ... existing route middleware ...
        'checkrole' => \App\Http\Middleware\CheckRole::class,
    ];
} 