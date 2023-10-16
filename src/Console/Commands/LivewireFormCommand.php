<?php

namespace Hexters\Wirehmvc\Console\Commands;

use Illuminate\Support\Str;
use Livewire\Features\SupportConsoleCommands\Commands\FormCommand;

use function Laravel\Prompts\select;

class LivewireFormCommand extends FormCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'module:livewire-form {name} {--force} {--module= : Module target name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Livewire form class for module';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Form';

    /**
     * Attribute name
     *
     * @var string
     */
    protected $name;

    public function handle()
    {

        $name = $this->option('module');
        if (is_null($name)) {
            $name = select(label: 'Select an available module!', options: module_name_lists(), required: true);
        }

        $this->name = Str::of($name)->slug()->studly();

        parent::handle();
    }


    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        
        return base_path("Modules/" . str_replace('\\', '/', $name) . '.php');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return "Modules\\";
    }


    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    public function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . "\\{$this->name}\\Livewire\\Forms";
    }
}
