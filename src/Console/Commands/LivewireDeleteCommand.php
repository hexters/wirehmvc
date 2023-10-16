<?php

namespace Hexters\Wirehmvc\Console\Commands;

use Hexters\Wirehmvc\Features\ComponentParser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\DeleteCommand;
use PhpOption\Option;

use function Laravel\Prompts\select;

class LivewireDeleteCommand extends DeleteCommand
{

    protected $signature = 'module:livewire-delete {name} {--inline} {--force} {--test} {--module= : Module target name}';

    protected $description = 'Delete a Livewire component in the module';


    public function handle()
    {

        $name = $this->option('module');
        if (is_null($name)) {
            $name = select(label: 'Select an available module!', options: module_name_lists(), required: true);
        }

        $name = Str::of($name)->slug()->studly();
        $argumentName = Str::of($this->argument('name'));

        Config::set('livewire.class_namespace', $this->overiteNamespace($name));
        Config::set('livewire.view_path', $this->overiteViewPath($name));
        Config::set('livewire.view_name', $name->lower() . '::livewire.' . $argumentName->snake('-'));

        $this->preHhandle();
    }

    public function preHhandle()
    {
        $this->parser = new ComponentParser(
            config('livewire.class_namespace'),
            config('livewire.view_path'),
            $this->argument('name')
        );

        if (!$force = $this->option('force')) {

            $ask = select(label: "Are you sure you want to delete the following files?", options: ['Yes', 'No'], default: 'No', required: true);

            if (in_array($ask, ['No'])) {
                return;
            }
        }

        $inline = $this->option('inline');
        $test = $this->option('test');

        $class = $this->removeClass($force);
        if (!$inline) $view = $this->removeView($force);
        if ($test) $test = $this->removeTest($force);

        $this->line("<options=bold,reverse;fg=yellow> COMPONENT DESTROYED </> 🦖💫\n");
        $class && $this->line("<options=bold;fg=yellow>CLASS:</> {$this->parser->relativeClassPath()}");
        if (!$inline) $view && $this->line("<options=bold;fg=yellow>VIEW:</>  {$this->parser->relativeViewPath()}");
        if ($test) $test && $this->line("<options=bold;fg=yellow>Test:</>  {$this->parser->relativeTestPath()}");
    }

    /**
     * Overite namespace module
     *
     * @param [string] $module
     * @return string
     */
    protected function overiteNamespace($module)
    {
        return 'Modules\\' . $module . '\\Livewire';
    }

    /**
     * Overite view resource path
     *
     * @param [string] $module
     * @return string
     */
    protected function overiteViewPath($module)
    {
        return module_path($module, 'Resources/views/livewire');
    }
}
