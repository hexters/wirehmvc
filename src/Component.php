<?php

namespace Hexters\Wirehmvc;

use Illuminate\Support\Str;
use Livewire\Component as BaseComponent;

class Component extends BaseComponent
{
    public function rendering()
    {
        $this->setId([
            'id' => $this->getId(),
            'namespace' => config('livewire.class_namespace'),
            'view_path' => Str::of(config('livewire.view_path'))->replace(base_path(''), ''),
            'layout' => config('livewire.layout'),
        ]);
    }
}
