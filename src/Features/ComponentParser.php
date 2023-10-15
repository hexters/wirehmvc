<?php

namespace Hexters\Wirehmvc\Features;

use Livewire\Features\SupportConsoleCommands\Commands\ComponentParser as ParentComponentParser;

class ComponentParser extends ParentComponentParser
{

    public static function generatePathFromNamespace($namespace)
    {
        $name = str($namespace)->finish('\\');
        return base_path(str_replace('\\', '/', $name));
    }

    public function viewName()
    {
        return config('livewire.view_name');
    }
}
