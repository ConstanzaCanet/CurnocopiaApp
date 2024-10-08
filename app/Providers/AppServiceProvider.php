<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Events\Dispatcher;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Dispatcher $events): void
    {
        $this->app['events']->listen(BuildingMenu::class, function (BuildingMenu $event) {
        
        // Obtener las categorías
        $categories = Category::all();

        // Agregar cada categoría al menú en 'categories'
        foreach ($categories as $category) {
            $event->menu->addIn('categories', [
                'text' => $category->name,
                'url'  => route('products.byCategory', $category->id), // Crear la ruta adecuada para cada categoría
            ]);
        }
    });

        View::composer('dashboard', function ($view) {
            $view->with('products', Product::paginate(6));
        });

        /*view()->composer('*', function ($view) {
            $view->with('categories', Category::all());
        });*/

    }
}
