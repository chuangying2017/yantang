<?php

namespace App\Api\V1\Middleware;

use App\Repositories\Integral\SignRule\SignClass;
use Closure;

class IntegralSignMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param SignClass $signClass
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $signClass = new SignClass;

        $signData = $signClass
            ->setPath(config('services.localStorageFile.path'))
            ->setFile(config('services.localStorageFile.SignRule'))
            ->get();
        if ($signData['status'] < 1)
        {
            return response()->json(['status' => 2,'message' => '积分签到通道关闭'])->setStatusCode(201);
        }
        return $next($request);
    }
}
