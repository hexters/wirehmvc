<?php

namespace Hexters\Wirehmvc\Console\Commands;

use Hexters\Wirehmvc\Features\ComponentParser;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Livewire\Features\SupportConsoleCommands\Commands\MakeCommand;

use function Laravel\Prompts\select;

class MakeLivewireCommand extends MakeCommand
{

    protected $signature = 'module:make-livewire {name} {--force} {--inline} {--test} {--pest} {--stub= : If you have several stubs, stored in subfolders } {--module= : Module target name}';

    protected $description = 'Create a new Livewire component in component';

    public function handle()
    {

        $name = $this->option('module');
        if (is_null($name)) {
            $name = select(label: 'Select an available module!', options: module_name_lists(), required: true);
        }

        $name = Str::of($name)->slug()->studly();
        $argumentName = Str::of($this->argument('name'))
            ->replace('/', '.')
            ->snake('-')
            ->replace('.-', '.');

        Config::set('livewire.class_namespace', $this->overiteNamespace($name));
        Config::set('livewire.view_path', $this->overiteViewPath($name));
        Config::set('livewire.view_name', $name->lower() . '::livewire.' . $argumentName);

        $this->preHandle();
    }


    public function preHandle()
    {
        $this->parser = new ComponentParser(
            config('livewire.class_namespace'),
            config('livewire.view_path'),
            $this->argument('name'),
            $this->option('stub')
        );

        if (!$this->isClassNameValid($name = $this->parser->className())) {
            $this->line("<options=bold,reverse;fg=red> WHOOPS! </> 😳 \n");
            $this->line("<fg=red;options=bold>Class is invalid:</> {$name}");

            return;
        }

        if ($this->isReservedClassName($name)) {
            $this->line("<options=bold,reverse;fg=red> WHOOPS! </> 😳 \n");
            $this->line("<fg=red;options=bold>Class is reserved:</> {$name}");

            return;
        }

        $force = $this->option('force');
        $inline = $this->option('inline');
        $test = $this->option('test') || $this->option('pest');
        $testType = $this->option('test') ? 'phpunit' : 'pest';

        $showWelcomeMessage = $this->isFirstTimeMakingAComponent();

        $class = $this->createClass($force, $inline);
        $view = $this->createView($force, $inline);

        if ($test) {
            $test = $this->createTest($force, $testType);
        }

        if ($class || $view) {
            $this->line("<options=bold,reverse;fg=green> COMPONENT CREATED </> 🤙\n");
            $class && $this->line("<options=bold;fg=green>CLASS:</> {$this->parser->relativeClassPath()}");

            if (!$inline) {
                $view && $this->line("<options=bold;fg=green>VIEW:</>  {$this->parser->relativeViewPath()}");
            }

            if ($test) {
                $test && $this->line("<options=bold;fg=green>TEST:</>  {$this->parser->relativeTestPath()}");
            }

            if ($showWelcomeMessage && !app()->runningUnitTests()) {
                $this->writeWelcomeMessage();
            }
        }
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
