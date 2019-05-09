<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use App\Models\OperationLogs;
use App\Models\Permission;

/**
 * 全局的用户操作日志中间件
 */
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
            $user_id = (int)Auth::id();
            $user_name = Auth::user()->name;
        }

        if ('GET' != $request->method()) {
            $router_as = $request->route()->action['as'];
dump($request->route());exit;
            $parentName = '';
            $subName    = '';
            $currentName = '';
            if (!empty($router_as)) {
                $permisson = new Permission();
                $attributesArr = $permisson->getAllCacheAttributes();
                $currentName = $this->getCurrentOperate($attributesArr, $router_as);
//                echo $router_as;exit;
//                dump($currentName) ;exit;
//                $parentName = $this->getParentName($attributesArr, $router_as);
//                $subName = $this->getSubName($attributesArr, $router_as);

            }

//            echo $currentName;exit;

            // TODO NEXT TIME
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

    private function getParentName(array $operateArr, string $needle): string
    {

    }

    private function getSubName(array $operateArr, string $needle): string
    {

    }

    private function getCurrentOperate(array $operateArr, string $needle): string
    {
        foreach ($operateArr as $op) {
            $op1 = array_flip($op);
            print_r($op1);
            if (array_key_exists($needle, array_flip($op))) {
                return $op;
            }
        }
        return '';
    }

}
