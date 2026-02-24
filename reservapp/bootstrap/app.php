<?php

use App\Http\Middleware\Logueado;
use App\Http\Middleware\UsuarioAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UsuarioMecanico;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'mecanico' => UsuarioMecanico::class,
            'admin' => UsuarioAdmin::class,
            'logueado' => Logueado::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 👇 Añade esto dentro del bloque que ya tienes
        $exceptions->renderable(function (Throwable $e, $request) {
            $esPDO = $e instanceof \PDOException;
            $esQuery = $e instanceof \Illuminate\Database\QueryException;

            $enCadena = false;
            $prev = $e->getPrevious();
            while ($prev !== null) {
                if ($prev instanceof \PDOException) {
                    $enCadena = true;
                    break;
                }
                $prev = $prev->getPrevious();
            }

            if ($esPDO || $esQuery || $enCadena) {
                return response()->view('errors.database_error', [], 503);
            }
        });

    })->create();
