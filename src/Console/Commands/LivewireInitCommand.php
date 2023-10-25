<?php

namespace Hexters\Wirehmvc\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

use function Laravel\Prompts\select;

class LivewireInitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:livewire-init {--module= : Module target name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial livewire configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $module = $this->option('module');
        if (is_null($module)) {
            $module = select(label: 'Select an available module!', options: module_name_lists(), required: true);
        }

        $module = Str::of($module)->slug()->studly();

        $middlewareStub = Str::of(file_get_contents(__DIR__ . '/stubs/config.middleware.stub'))
            ->replace('{{ module }}', $module)
            ->replace('{{ moduleLower }}', strtolower($module))
            ->replace('{{ namespace }}', "Modules\\{$module}\\Http\\Middleware");

        $middleware = module_path($module, "Http/Middleware/LivewireSetup{$module}Middleware.php");
        if (!file_exists($middleware)) {
            file_put_contents($middleware, $middlewareStub);

            $this->components->info("Middleware [$middleware] created successfully");
        } else {
            $this->components->info("Middleware [$middleware] file already exists");
        }

        $providerStub = Str::of(file_get_contents(__DIR__ . '/stubs/provider.route.stub'))
            ->replace('{{ module }}', $module)
            ->replace('{{ moduleLower }}', strtolower($module))
            ->replace('{{ namespace }}', "Modules\\{$module}\\Providers");

        $provider = module_path($module, "Providers/RouteServiceProvider.php");
        file_put_contents($provider, $providerStub);
        $this->components->info("Provider [$provider] updated successfully");

        $this->call('module:make-livewire', [
            'name' => 'Welcome',
            '--module' => $module,
        ]);


        $welcome = file_get_contents(__DIR__ . '/stubs/welcome.blade.stub');
        $welcomeTarget = module_path($module, 'Resources/views/livewire/welcome.blade.php');
        file_put_contents($welcomeTarget, $welcome);

        $routeStube = Str::of(file_get_contents(__DIR__ . '/stubs/route.stub'))
            ->replace('{{ module }}', $module)
            ->replace('{{ moduleLower }}', strtolower($module));

        $route = module_path($module, "routes/web.php");
        file_put_contents($route, $routeStube);
        $this->components->info("Route [$route] updated successfully");


        $providerName = "{$module}ServiceProvider";
        $provider = file_get_contents(__DIR__ . '/stubs/provider.load.stub');


        $providerStub = Str::of($provider)
            ->replace('{{ module }}', strtolower($module))
            ->replace('{{ namespace }}', "Modules\\{$module}\\Providers")
            ->replace('{{ class }}', $providerName);

        $providerTarget = module_path($module, "Providers/{$providerName}.php");
        file_put_contents($providerTarget, $providerStub);
    }
}
