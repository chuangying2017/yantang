<?php

namespace App\Api\V1\Middleware\Integral;

use Closure;
use Illuminate\Support\Facades\Validator;

class ExchangeMiddleware
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
        $validator =  Validator::make($request->all(),[
            'cost_integral'     =>  'required|numeric|min:1',
            'promotions_id'     =>  'required|numeric|min:1',
            'valid_time'        =>  'required|date|after:today',
            'deadline_time'     =>  'required|date|after:valid_time',
            'status'            =>  'sometimes|numeric',
            'type'              =>  'sometimes|string',
            'issue_num'         =>  'required|numeric|min:1',
            'draw_num'          =>  'sometimes|numeric',
            'remain_num'        =>  'sometimes|numeric',
            'delayed'           =>  'required|numeric|min:1',
            'cover_image'       =>  'sometimes|url',
            'member_type'       =>  'required|numeric',
            'limit_num'         =>  'required|numeric|min:1',
        ]);
        if ($validator->fails())
        {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('error data format is that fatal parameters comparison.', $validator->errors());
        }
        return $next($request);
    }
}
