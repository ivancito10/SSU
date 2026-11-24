<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role; 

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Gate::define('fisioterapia.acceder', fn ($user) => $user->hasAnyRole(['Encargado de Fisioterapia', 'Fisioterapeuta']));
        Gate::define('fisioterapia.ordenes.ver', fn ($user) => $user->hasRole('Encargado de Fisioterapia'));
        Gate::define('fisioterapia.administrar', fn ($user) => $user->hasRole('Encargado de Fisioterapia'));
        Gate::define('fisioterapia.tecnicas.ver', fn ($user) => $user->hasAnyRole(['Encargado de Fisioterapia', 'Fisioterapeuta']));
        Gate::define('fisioterapia.tecnicas.gestionar', fn ($user) => $user->hasRole('Encargado de Fisioterapia'));

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        //
    }
}