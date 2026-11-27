<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/aportes/aportes.php'));

            Route::middleware('web')
                ->group(base_path('routes/administracion/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/afiliacion/afiliacion.php'));

            Route::middleware('web')
                ->group(base_path('routes/vigencia/vigencia.php'));

            Route::middleware('web')
                ->group(base_path('routes/plataforma/plataforma.php'));

            Route::middleware('web')
                ->group(base_path('routes/archivo_clinico/archivo_clinico.php'));
            
            Route::middleware('web')
                ->group(base_path('routes/atencion_medica/atencion_medica.php'));

            Route::middleware('web')
                ->group(base_path('routes/Publicos/publicos.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        // API general
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?: $request->ip());
        });

        // Certificado No Afiliación (formulario público)
        RateLimiter::for('certificado-no-afiliacion', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip())
                ->response(function () {
                    return response()->json([
                        'message' => 'Demasiados intentos. Intente nuevamente en unos minutos.'
                    ], 429);
                });
        });
    }
}
