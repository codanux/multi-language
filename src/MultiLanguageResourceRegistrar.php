<?php

namespace Codanux\MultiLanguage;



use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use InvalidArgumentException;

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
class MultiLanguageResourceRegistrar
{
    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    /**
     * The parameters set for this resource instance.
     *
     * @var array|string
     */
    protected $parameters;

    /**
     * The global parameter mapping.
     *
     * @var array
     */
    protected static $parameterMap = [];

    /**
     * Singular global parameters.
     *
     * @var bool
     */
    protected static $singularParameters = true;

    /**
     * The verbs used in the resource URIs.
     *
     * @var array
     */
    protected static $verbs = [
        'create' => 'create',
        'edit' => 'edit',
    ];

    /**
     * Create a new resource registrar instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
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

        // If the resource name contains a slash, we will assume the developer wishes to
        // register these resource routes with a prefix so we will set that up out of
        // the box so they don't have to mess with it. Otherwise, we will continue.
        if (Str::contains($name, '/')) {
            $this->prefixedResource($name, $controller, $options);

            return;
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $base = $this->getResourceWildcard(last(explode('.', $name)));

        $defaults = $this->resourceDefaults;

        foreach ($this->getResourceMethods($defaults, $options) as $m) {

            $action = $this->getResourceAction($name, $controller, $m, $options);

            $options['name'] = $action['as'];
            if (isset($action['parent']))
                $options['parent'] = $action['parent'];

            switch ($m)
            {
                case "index":
                    $route = $this->router->locale("{$name}.index", $controller.'@'.$m, $options);
                    break;
                case "create":
                    $route = $this->router->locale("{$name}.create", $controller.'@'.$m, $options);
                    break;
                case "store":
                    $route = $this->router->locale("{$name}.create", $controller.'@'.$m, $options)->method('POST');
                    break;
                case "show":
                    $route = $this->router->locale("{$name}.show", $controller.'@'.$m, $options);
                    break;
                case "edit":
                    $route = $this->router->locale("{$name}.edit", $controller.'@'.$m, $options);
                    break;
                case "update":
                    $route = $this->router->locale("{$name}.edit", $controller.'@'.$m, $options)->method('PUT');
                    break;
                case "destroy":
                    $route = $this->router->locale("{$name}.destroy", $controller.'@'.$m, $options)->method('DELETE');
                    break;
            }

            if (isset($options['bindingFields'])) {
                $this->setResourceBindingFields($route, $options['bindingFields']);
            }

        }

    }

    /**
     * Build a set of prefixed resource routes.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return void
     */
    protected function prefixedResource($name, $controller, array $options)
    {
        [$name, $prefix] = $this->getResourcePrefix($name);

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $callback = function ($me) use ($name, $controller, $options) {
            $me->resource($name, $controller, $options);
        };

        return $this->router->group(compact('prefix'), $callback);
    }

    /**
     * Extract the resource and prefix from a resource name.
     *
     * @param  string  $name
     * @return array
     */
    protected function getResourcePrefix($name)
    {
        $segments = explode('/', $name);

        // To get the prefix, we will take all of the name segments and implode them on
        // a slash. This will generate a proper URI prefix for us. Then we take this
        // last segment, which will be considered the final resources name we use.
        $prefix = implode('/', array_slice($segments, 0, -1));

        return [end($segments), $prefix];
    }

    /**
     * Get the applicable resource methods.
     *
     * @param  array  $defaults
     * @param  array  $options
     * @return array
     */
    protected function getResourceMethods($defaults, $options)
    {
        $methods = $defaults;

        if (isset($options['only'])) {
            $methods = array_intersect($methods, (array) $options['only']);
        }

        if (isset($options['except'])) {
            $methods = array_diff($methods, (array) $options['except']);
        }

        return $methods;
    }

    /**
     * Add the index method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceIndex($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'index', $options);

        return $this->router->locale("{$name}.index", $action, $options);
    }

    /**
     * Add the create method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceCreate($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'create', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.create", $action, $options);
    }

    /**
     * Add the store method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceStore($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'store', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.create", $action, $options)->method('POST');
    }

    /**
     * Add the show method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceShow($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'show', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.show", $action, $options);
    }

    /**
     * Add the edit method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceEdit($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'edit', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.edit", $action, $options);
    }

    /**
     * Add the update method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.edit", $action, $options)->method('PUT');
    }

    /**
     * Add the destroy method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceDestroy($name, $base, $controller, $options)
    {
        // $uri = $this->getResourceUri($name);

        $action = $this->getResourceAction($name, $controller, 'destroy', $options);

        $options['as'] = $action['as'];

        return $this->router->locale("{$name}.destroy", $action, $options)->method('DELETE');
    }

    /**
     * Get the name for a given resource with shallowness applied when applicable.
     *
     * @param  string  $name
     * @param  array  $options
     * @return string
     */
    protected function getShallowName($name, $options)
    {
        return isset($options['shallow']) && $options['shallow']
            ? last(explode('.', $name))
            : $name;
    }

    /**
     * Set the route's binding fields if the resource is scoped.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  array  $bindingFields
     * @return void
     */
    protected function setResourceBindingFields($route, $bindingFields)
    {
        preg_match_all('/(?<={).*?(?=})/', $route->uri, $matches);

        $fields = array_fill_keys($matches[0], null);

        $route->setBindingFields(array_replace(
            $fields, array_intersect_key($bindingFields, $fields)
        ));
    }

    /**
     * Get the base resource URI for a given resource.
     *
     * @param  string  $resource
     * @return string
     */
    public function getResourceUri($resource)
    {
        if (! Str::contains($resource, '.')) {

            return $resource;
        }

        // Once we have built the base URI, we'll remove the parameter holder for this
        // base resource name so that the individual route adders can suffix these
        // paths however they need to, as some do not have any parameters at all.
        $segments = explode('.', $resource);

        $uri = $this->getNestedResourceUri($segments);

        return str_replace('/{'.$this->getResourceWildcard(end($segments)).'}', '', $uri);
    }

    /**
     * Get the URI for a nested resource segment array.
     *
     * @param  array  $segments
     * @return string
     */
    protected function getNestedResourceUri(array $segments)
    {
        // We will spin through the segments and create a place-holder for each of the
        // resource segments, as well as the resource itself. Then we should get an
        // entire string for the resource URI that contains all nested resources.
        return implode('/', array_map(function ($s) {
            return $s.'/{'.$this->getResourceWildcard($s).'}';
        }, $segments));
    }

    /**
     * Format a resource parameter for usage.
     *
     * @param  string  $value
     * @return string
     */
    public function getResourceWildcard($value)
    {
        if (isset($this->parameters[$value])) {
            $value = $this->parameters[$value];
        } elseif (isset(static::$parameterMap[$value])) {
            $value = static::$parameterMap[$value];
        } elseif ($this->parameters === 'singular' || static::$singularParameters) {
            $value = Str::singular($value);
        }

        return str_replace('-', '_', $value);
    }

    /**
     * Get the action array for a resource route.
     *
     * @param  string  $resource
     * @param  string  $controller
     * @param  string  $method
     * @param  array  $options
     * @return array
     */
    protected function getResourceAction($resource, $controller, $method, $options)
    {
        $name = $this->getResourceRouteName($resource, $method, $options);

        $action = ['as' => $name, 'uses' => $controller.'@'.$method];

        if (isset($options['middleware'])) {
            $action['middleware'] = $options['middleware'];
        }

        if (isset($options['excluded_middleware'])) {
            $action['excluded_middleware'] = $options['excluded_middleware'];
        }

        if (isset($options['wheres'])) {
            $action['where'] = $options['wheres'];
        }


        if (isset($options['parents']) && isset($options['parents'][$name])) {
            $parent = $options['parents'][$name];

            if ($parent == $name)
            {
                throw new InvalidArgumentException("Parent and Name cannot be the same.");
            }
            $action['parent'] = $parent;
        }

        return $action;
    }

    /**
     * Get the name for a given resource.
     *
     * @param  string  $resource
     * @param  string  $method
     * @param  array  $options
     * @return string
     */
    protected function getResourceRouteName($resource, $method, $options)
    {
        $name = $resource;

        // If the names array has been provided to us we will check for an entry in the
        // array first. We will also check for the specific method within this array
        // so the names may be specified on a more "granular" level using methods.
        if (isset($options['names'])) {
            if (is_string($options['names'])) {
                $name = $options['names'];
            } elseif (isset($options['names'][$method])) {
                return $options['names'][$method];
            }
        }

        // If a global prefix has been assigned to all names for this resource, we will
        // grab that so we can prepend it onto the name when we create this name for
        // the resource action. Otherwise we'll just use an empty string for here.
        $prefix = isset($options['as']) ? $options['as'].'.' : '';

        return trim(sprintf('%s%s.%s', $prefix, $name, $method), '.');
    }

    /**
     * Set or unset the unmapped global parameters to singular.
     *
     * @param  bool  $singular
     * @return void
     */
    public static function singularParameters($singular = true)
    {
        static::$singularParameters = (bool) $singular;
    }

    /**
     * Get the global parameter map.
     *
     * @return array
     */
    public static function getParameters()
    {
        return static::$parameterMap;
    }

    /**
     * Set the global parameter mapping.
     *
     * @param  array  $parameters
     * @return void
     */
    public static function setParameters(array $parameters = [])
    {
        static::$parameterMap = $parameters;
    }

    /**
     * Get or set the action verbs used in the resource URIs.
     *
     * @param  array  $verbs
     * @return array
     */
    public static function verbs(array $verbs = [])
    {
        if (empty($verbs)) {
            return static::$verbs;
        } else {
            static::$verbs = array_merge(static::$verbs, $verbs);
        }
    }
}
