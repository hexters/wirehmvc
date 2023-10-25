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

    public function classContents($inline = false)
    {
        $stubName = $inline ? 'livewire.inline.stub' : 'livewire.stub';

        $stub_path = __DIR__ . '/../Console/Commands/stubs/';

        $template = file_get_contents($stub_path . $stubName);

        if ($inline) {
            $template = preg_replace('/\[quote\]/', $this->wisdomOfTheTao(), $template);
        }

        return preg_replace(
            ['/\[namespace\]/', '/\[class\]/', '/\[view\]/'],
            [$this->classNamespace(), $this->className(), $this->viewName()],
            $template
        );
    }
}
