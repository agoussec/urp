<?php

namespace Agoussec\URP;

use Agoussec\URP\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Agoussec\URP\Console\AddRole;
use Agoussec\URP\Console\ListRoles;
use Agoussec\URP\Console\AddPermission;
use Agoussec\URP\Console\ListPermissions;
use Agoussec\URP\Console\AssignRole;
use Agoussec\URP\Middleware\RoleMiddleware;
use Illuminate\Routing\Router;


class PermissionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddRole::class,
                ListRoles::class,
                AddPermission::class,
                ListPermissions::class,
                AssignRole::class,
            ]);
        }

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', RoleMiddleware::class);


        //  Load MIGRATION FILES
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'migrations');

        try {
            Permission::get()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission);
                });
            });

        } catch (\Exception $e) {
            report($e);
            return false;
        }

        //Blade directives
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})) { ?>";
        });

        Blade::directive('endrole', function ($role) {
            return "<?php } ?>";
        });
    }
}
