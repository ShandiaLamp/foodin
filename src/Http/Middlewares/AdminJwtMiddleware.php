<?php

namespace Shandialamp\Foodin\Http\Middlewares;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Shandialamp\Foodin\Http\Resources\AdminResource;
use Shandialamp\Foodin\Services\UserService;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminJwtMiddleware
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            
            $token = JWTAuth::getToken();
            $user = Auth::guard('admin')->setToken($token)->user();
            if (!$user) {
                return AdminResource::error('凭证不存在', 401, '凭证不存在', null, 401);
            }
            if (!$this->userService->existsTokenByUserId($user->id, $token)) {
                return AdminResource::error('凭证不存在', 401, '凭证不存在', null, 401);
            }
        } catch (JWTException $e) {
            return AdminResource::error('凭证不存在', 401, '凭证不存在', null, 401);
        }
        return $next($request);
    }
}
