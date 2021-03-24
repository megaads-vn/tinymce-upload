<?php 
namespace Megaads\TinymceUpload\Middleware;

use Closure;
use Config;

class UploadMiddleware 
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
        // Status flag:
        $loginSuccessful = false;
        // Check username and password:
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            if ($username == Config::get('app.basicAuthentication.username') && $password == Config::get('app.basicAuthentication.password')){
                $loginSuccessful = true;
            }
        }
        if ($loginSuccessful){
            return $next($request);
        }else{
            return response('Unauthorized.', 401,["WWW-Authenticate"=>"Basic realm='Findcouponhere'"]);
        }
    }
}