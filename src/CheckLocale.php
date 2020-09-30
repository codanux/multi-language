<?php

namespace Codanux\MultiLanguage;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class CheckLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->segment(1), config('multi-language.locales'))) {
            $this->change( Request::segment(1));
        }
        else if (\session('locale') && ! in_array($request->segment(1), config('multi-language.locales'))) {
            $this->change(config('multi-language.default_locale'));
        } elseif (! in_array($request->segment(1), config('multi-language.locales'))) {
            $this->change(config('multi-language.default_locale'));
        }


        return $next($request);
    }


    private function change($locale){
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }
}
