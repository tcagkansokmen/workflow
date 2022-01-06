<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPower
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $explode = explode('-', $role);
        $field = $explode[0];
        $action = $explode[1];
        if (Auth::check()) {
            $user = Auth::user();
            $power = $user->power($field, $action);
            if($power){
              return $next($request);
            }
        }
        if (! $request->expectsJson()) {
          return redirect()->guest(Auth::user()->group->redirect);
        }else{
          $result = array(
            'status' => 0,
            'message' => 'Bu işleme yetkiniz bulunmamaktadır.'
          );
          return response()->json($result);
        }
    }
}
