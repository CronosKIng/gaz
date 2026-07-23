<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.password' => \App\Http\Middleware\CheckDefaultPassword::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/pay-bills-save',
            'api/pay-bills-delete',
            'api/staff-students-add',
            'api/staff-students-remove',
            'api/staff-students-pay',
            'api/staff-students-view',
            'api/payment/*',
            'api/student-payment/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
