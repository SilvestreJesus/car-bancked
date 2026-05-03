<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // <--- Asegúrate de que esta línea esté presente
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Excluimos las rutas de la API de la verificación CSRF 
        // para que Vue pueda hacer POST desde Railway.
        $middleware->validateCsrfTokens(except: [
            'api/*', 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();