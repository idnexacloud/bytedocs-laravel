<?php

namespace ByteDocs\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \ByteDocs\Laravel\Core\APIDocs getInstance()
 * @method static void addRoute(\Illuminate\Routing\Route $route)
 * @method static void addRouteInfo(\ByteDocs\Laravel\Core\RouteInfo $routeInfo) 
 * @method static void generate()
 * @method static \ByteDocs\Laravel\Core\Documentation getDocumentation()
 * @method static \ByteDocs\Laravel\Core\Config getConfig()
 * @method static array getOpenAPIJSON()
 * @method static string getAPIContext()
 * @method static array handleChat(\ByteDocs\Laravel\AI\ChatRequest $request)
 *
 * @see \ByteDocs\Laravel\Core\APIDocs
 */
class ByteDocs extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'bytedocs';
    }
}