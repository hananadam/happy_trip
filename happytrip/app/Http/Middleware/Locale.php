<?php

namespace App\Http\Middleware;
use App\Providers\RouteServiceProvider;
use Closure;
use App;
use Config;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Locale
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
   public function handle(Request $request, Closure $next)
   {
     $raw_locale = Session::get('locale');
     //$raw_locale = $request->session()->get('locale');
     if (in_array($raw_locale, Config::get('app.locales'))) {
       $locale = $raw_locale;
     }
     else $locale = Config::get('app.locale');
       App::setLocale($locale);
       //dd($locale); die;

       return $next($request);
   }


}

