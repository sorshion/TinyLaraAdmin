<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OperationLogs;

class AdminOperationLogs
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
        $user_id = 0;
        $user_name = '';
        if (Auth::check()) {
            $user_id = (int) Auth::id();
            $user_name = Auth::user()->name;
        }
        if ('GET' != $request->method()) {
            $input = $request->all();
            $log = new OperationLogs();
            $log->user_id = $user_id;
            $log->user_name = $user_name;
            $log->path  = $request->path();
            $log->method = $request->method();
            $log->ip = $request->ip();
            $log->input = json_encode($input, JSON_UNESCAPED_UNICODE);
            $log->save();
        }
        return $next($request);
    }
}
