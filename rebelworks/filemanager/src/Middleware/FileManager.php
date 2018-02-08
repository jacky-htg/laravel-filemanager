<?php

namespace Rebelworks\Filemanager\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class FileManager
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
      * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    { 
        if(!$this->auth->authenticate()){
            return redirect('/login')->with('info', 'Please login first.');
        }
        
        $s3 = \Storage::disk(config('filemanager.disk'));
        /*if (!$s3->has(config('filemanager.prefix'))){
            $s3->makeDirectory(config('filemanager.prefix'));
        }*/
        
        if (!$s3->has(config('filemanager.prefix')."/".\Auth::user()->id)) {
            $s3->makeDirectory(config('filemanager.prefix')."/".\Auth::user()->id);
        }
        
        if (!$s3->has(config('filemanager.prefix').'shared')){
            $s3->makeDirectory(config('filemanager.prefix').'/shared');
        }
        
        return $next($request);
    }
}
