<?php

namespace Codanux\MultiLanguage;

use Closure;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class DetectRequestLocale
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
        if (in_array($request->segment(1), array_keys(config('multi-language.locales')))) {
            $this->change($request->segment(1));
        }
        else {
            $this->change(Session::get('locale', config('multi-language.default_locale')));
        }

        return $next($request);
    }


    private function change($locale){
        Session::put('locale', $locale);
        app()->setLocale($locale);
    }
}
