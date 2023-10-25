<?php

namespace Hexters\Wirehmvc\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class WireHmvcMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        $this->parseRequest($request);

        return $next($request);
    }

    protected function parseRequest(Request $request)
    {
        
        foreach ($request->components as $component) {
            $snapshot = json_decode($component['snapshot'], true);
            $memo = $snapshot['memo'];
            if (is_array($data = $memo['id'])) {
                Config::set('livewire.view_path', base_path($data['view_path']));
                Config::set('livewire.class_namespace', $data['namespace']);
                Config::set('livewire.layout', $data['layout']);
            }
        }

        
    }
}
