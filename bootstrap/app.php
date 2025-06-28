<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsDirigente;
use App\Http\Middleware\IsInvitado;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //aca registramos los middleare alias, que se usan para poder hacer las validacionesd tipos de usuarios
        $middleware->alias([
            'admin' => IsAdmin::class,
            'dirigente' => IsDirigente::class,
            'invitado' => IsInvitado::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
