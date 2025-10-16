<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;   
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController; 
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Paginator::useBootstrap();

        // 1. Registramos los singletons UNA SOLA VEZ por petición.
        // Esto asegura que la lógica pesada (consultas a BD, etc.) solo se ejecute la primera vez que se necesiten los datos.
        $this->app->singleton('globalFuntion_cashier', function () {
            $controller = new Controller();
            // return $controller->cashier(null, Auth::check() ? Auth::user()->id : null, 'status <> "cerrada"');
            return $controller->cashier(null, Auth::check() ? 'user_id = "'.Auth::user()->id.'"' : null, 'status <> "cerrada"');
        });
 
        $this->app->singleton('globalFuntion_cashierMoney', function () {
            $controller = new Controller();
            return $controller->cashierMoney(null, Auth::check() ? 'user_id = "'.Auth::user()->id.'"' : null, 'status = "abierta"')->original;
        });

        // 2. Usamos el View Composer para COMPARTIR los datos ya resueltos (o por resolver una vez) con todas las vistas.
        View::composer('*', function ($view) {
            $currentRouteName = Route::currentRouteName();
            $view->with('globalFuntion_cashier', $this->app->make('globalFuntion_cashier'));
            $view->with('globalFuntion_cashierMoney', $this->app->make('globalFuntion_cashierMoney'));
            if ($currentRouteName !== 'cashiers.close') {
                // $view->with('globalFuntion_cashierMoney', $this->app->make('globalFuntion_cashierMoney'));
            }
        });

        // Solo Para la vista Index
        View::composer('voyager::index', function ($view) {
            $new = new IndexController();
            $view->with('global_index', $new->IndexSystem(null)->original);
        });
    }
}
