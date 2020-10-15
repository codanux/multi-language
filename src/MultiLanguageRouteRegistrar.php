<?php

namespace Codanux\MultiLanguage;

use BadMethodCallException;
use Closure;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

use Illuminate\Routing\Router;

/**
 * @method \Illuminate\Routing\Route get(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route post(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route put(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route delete(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route patch(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route options(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\Route any(string $uri, \Closure|array|string|null $action = null)
 * @method \Illuminate\Routing\RouteRegistrar as(string $value)
 * @method \Illuminate\Routing\RouteRegistrar domain(string $value)
 * @method \Illuminate\Routing\RouteRegistrar middleware(array|string|null $middleware)
 * @method \Illuminate\Routing\RouteRegistrar name(string $value)
 * @method \Illuminate\Routing\RouteRegistrar namespace(string $value)
 * @method \Illuminate\Routing\RouteRegistrar prefix(string  $prefix)
 * @method \Illuminate\Routing\RouteRegistrar where(array  $where)
 */
class MultiLanguageRouteRegistrar
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The attributes to pass on to the router.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The methods to dynamically pass through to the router.
     *
     * @var array
     */
    protected $passthru = [
        'get', 'post', 'put', 'patch', 'delete', 'options', 'any',
    ];

    /**
     * The attributes that can be set through this class.
     *
     * @var array
     */
    protected $allowedAttributes = [
        'middleware', 'excluded_middleware', 'method', 'name', 'prefix', 'parent'
    ];

    protected $localeAttributes = [
        'name',
    ];

    /**
     * The attributes that are aliased.
     *
     * @var array
     */
    protected $aliases = [
        'name' => 'as',
    ];

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Set the value for a given attribute.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function attribute($key, $value)
    {
        if (! in_array($key, $this->allowedAttributes)) {
            throw new InvalidArgumentException("Attribute [{$key}] does not exist.");
        }

        $this->attributes[Arr::get($this->aliases, $key, $key)] = $value;

        return $this;
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     */
    public function resource($name, $controller, array $options = [])
    {
        return $this->router->resource($name, $controller, $this->attributes + $options);
    }

    /**
     * Route an API resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\PendingResourceRegistration
     */
    public function apiResource($name, $controller, array $options = [])
    {
        return $this->router->apiResource($name, $controller, $this->attributes + $options);
    }

    /**
     * Create a route group with shared attributes.
     *
     * @param  \Closure|string  $callback
     * @return void
     */
    public function group($callback)
    {
        $this->router->group($this->attributes, $callback);
    }

    /**
     * Register a new route with the given verbs.
     *
     * @param  array|string  $methods
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Illuminate\Routing\Route
     */
    public function match($methods, $uri, $action = null)
    {
        return $this->router->match($methods, $uri, $this->compileAction($action));
    }

    /**
     * Register a new route with the router.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  \Closure|array|string|null  $action
     * @return \Illuminate\Routing\Route
     */
    protected function  registerRoute($method, $uri, $action = null)
    {
        if (! is_array($action)) {
            $action = array_merge($this->attributes, $action ? ['uses' => $action] : []);
        }

        return $this->router->{$method}($uri, $this->compileAction($action));
    }

    /**
     * Compile the action into an array including the attributes.
     *
     * @param  \Closure|array|string|null  $action
     * @return array
     */
    protected function compileAction($action)
    {
        if (is_null($action)) {
            return $this->attributes;
        }

        if (is_string($action) || $action instanceof Closure) {
            $action = ['uses' => $action];
        }

        if (is_array($action) &&
            is_callable($action) &&
            ! Arr::isAssoc($action)) {
            $action = [
                'uses' => $action[0].'@'.$action[1],
                'controller' => $action[0].'@'.$action[1],
            ];
        }

        return array_merge($this->attributes, $action);
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\RouteCollection
     */
    public function register($name, $controller, array $options = [])
    {
        if (isset($options['parameters']) && ! isset($this->parameters)) {
            $this->parameters = $options['parameters'];
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.

        $collection = new RouteCollection;

        foreach (config('multi-language.locales') as $locale)
        {
            if (! key_exists('name', $options))
            {
                $options['name'] = $name;
            }

            #region Group Stack
            $prefix = true;

            $stacks = $this->router->getGroupStack();

            $stack = end($stacks);

            if (key_exists('localePrefix', $stack))
            {
                if (is_array($stack['localePrefix']))
                {
                    $pr = null;
                    foreach (data_get($stack, 'localePrefix', []) as $key)
                    {
                        $pr = $pr.'/'.MultiLanguage::generateUri($key, $locale, $prefix);
                        $prefix = false;
                    }

                    $this->attribute('prefix', $pr);
                }
                else {
                    $this->attribute('prefix', MultiLanguage::generateUri($stack['localePrefix'], $locale));
                    $prefix = false;
                }
            }

            #endregion

            #region Register Attributes
            foreach ($options as $key => $attribute)
            {
                if (in_array($key, $this->localeAttributes))
                {
                    $this->attribute($key, "{$locale}.{$attribute}");
                }
                else {
                    $this->attribute($key, $attribute);
                }
            }

            #endregion

            // Generate Route
            $uri = MultiLanguage::generateUri($name, $locale, $prefix);

            $method = $options['method'] ?? "get";

            $route = $this->registerRoute($method, $uri, $controller);

            $collection->add($route);
        }

        return $collection;
    }
}
