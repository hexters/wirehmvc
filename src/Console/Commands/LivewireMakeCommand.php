<?php

namespace Hexters\Wirehmvc\Console\Commands;

class LivewireMakeCommand extends MakeLivewireCommand
{

    protected $signature = 'module:livewire-make {name} {--force} {--inline} {--test} {--pest} {--stub= : If you have several stubs, stored in subfolders } {--module= : Module target name}';
    
}
