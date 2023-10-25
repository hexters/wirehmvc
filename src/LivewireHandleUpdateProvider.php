<?php

namespace Hexters\Wirehmvc;

use Hexters\Wirehmvc\Middleware\WireHmvcMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class LivewireHandleUpdateProvider extends ServiceProvider
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
        $this->setupLivewireUpdateRoute();
    }

    protected function setupLivewireUpdateRoute()
    {
        
        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/wirehmvc/update', $handle)
                ->middleware(['web', WireHmvcMiddleware::class]);
        });
    }
}
