<?php

namespace Hexters\Wirehmvc;

use App\Http\Middleware\WireHmvcMiddleware;
use Hexters\Wirehmvc\Console\Commands\LivewireAttributeCommand;
use Hexters\Wirehmvc\Console\Commands\LivewireDeleteCommand;
use Hexters\Wirehmvc\Console\Commands\LivewireFormCommand;
use Hexters\Wirehmvc\Console\Commands\LivewireInitCommand;
use Hexters\Wirehmvc\Console\Commands\LivewireLayoutCommand;
use Hexters\Wirehmvc\Console\Commands\MakeLivewireCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WireHmvcServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/config.php',
            'wirehmvc'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommand();
    }

    protected function registerCommand()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeLivewireCommand::class,
                LivewireFormCommand::class,
                LivewireAttributeCommand::class,
                LivewireDeleteCommand::class,
                LivewireLayoutCommand::class,
                LivewireInitCommand::class,
            ]);
        }
    }
}
