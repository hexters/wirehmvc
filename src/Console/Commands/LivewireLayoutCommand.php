<?php

namespace Hexters\Wirehmvc\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
use Livewire\Features\SupportConsoleCommands\Commands\LayoutCommand;

use function Laravel\Prompts\select;

class LivewireLayoutCommand extends LayoutCommand
{
    protected $signature = 'module:livewire-layout {--force} {--stub= : If you have several stubs, stored in subfolders } {--module= : Target module name}';

    protected $description = 'Create new application layout files for specific modules';


    public function handle()
    {

        $name = $this->option('module');
        if (is_null($name)) {
            $name = select(label: 'Select an available module!', options: module_name_lists(), required: true);
        }

        $name = Str::of($name)->slug()->studly();

        $baseViewPath = module_path($name, 'Resources/views');

        $layout = str('components.layouts.app');

        $layoutPath = $this->layoutPath($baseViewPath, $layout);

        $relativeLayoutPath = $this->relativeLayoutPath($layoutPath);

        $force = $this->option('force');

        $stubPath = $this->stubPath($this->option('stub'));

        if (File::exists($layoutPath) && !$force) {
            $this->line("<fg=red;options=bold>View already exists:</> {$relativeLayoutPath}");

            
        } else {
            $this->ensureDirectoryExists($layoutPath);

            $result = File::copy($stubPath, $layoutPath);

            if ($result) {
                $this->line("<options=bold,reverse;fg=green> LAYOUT CREATED </> 🤙\n");
                $this->line("<options=bold;fg=green>CLASS:</> {$relativeLayoutPath}");
            }
        }


        $this->createMiddleware($name);
    }

    protected function createMiddleware(Stringable $name)
    {
        $namespace = "Modules\\{$name}\\Http\\Middleware";
        $stub = Str::of(file_get_contents(__DIR__ . '/stubs/layout.middleware.stub'))
            ->replace('{{ module }}', $name)
            ->replace('{{ moduleLower }}', $name->lower())
            ->replace('{{ namespace }}', $namespace);

        $targetFile = module_path($name, "Http/Middleware/Layout{$name}Middlware.php");
        if (!file_exists($targetFile)) {
            file_put_contents($targetFile, $stub);
            $this->line("<options=bold;fg=green>CLASS:</> {$targetFile}");
        } else {
            $this->line("<fg=red;options=bold>Middleware already exists:</> {$targetFile}");
        }
    }
}
