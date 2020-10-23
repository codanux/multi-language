@props(['route', 'prefix' => false])
@php
    $name = substr($route->getName(), strpos($route->getName(), '.') + 1);
@endphp
<ul class="flex text-gray-500 text-sm lg:text-base">
    @if($parent = $route->getAction('parent'))
        @if(Route::hasLocale($parent))
            <x-breadcrumb
                :prefix="true"
                :route="Route::getRoutes()->getByName(app()->getLocale().'.'.$parent)"
            ></x-breadcrumb>
        @endif
    @endif

    <li class="inline-flex items-center">
        <a href="{{ route($route->getName(), request()->route()->parameters()) }}">
            {{ trans($route->getAction('label'), collect(request()->route()->parameters())->map(function ($a) { return $a instanceof \Illuminate\Database\Eloquent\Model ? $a->getLabel() : $a; })->toArray()) }}
        </a>

        @if($prefix)
        <svg class="h-5 w-auto text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"
            ></path>
        </svg>
        @endif
    </li>
</ul>

