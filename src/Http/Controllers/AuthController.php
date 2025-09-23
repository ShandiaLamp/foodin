<?php

namespace Shandialamp\Foodin\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Shandialamp\Foodin\Exceptions\ServiceException;
use Shandialamp\Foodin\Http\Resources\AdminResource;
use Shandialamp\Foodin\Models\UserToken;
use Shandialamp\Foodin\Services\MenuService;
use Shandialamp\Foodin\Services\UserService;

class AuthController
{
    protected UserService $userService;
    protected MenuService $menuService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->menuService = new MenuService();
    }

    public function login(Request $request)
    {
        $token = null;
        try {
            $token = $this->userService->login(
                $request->get('username', 'admin'),
                $request->get('password', '123456'),
                $request->get('scope', 'web'),
            );
        } catch (ServiceException $e) {
            return AdminResource::error($e->getMessage(), 400);
        }
        $user = auth('admin')->user();
        return AdminResource::success([
            'accessToken'   => $token->token,
            'id'    => $user->id,
            'username'  => $user->username,
            'realName'  => $user->real_name ?? '用户',
            'roles' => [],
        ], '登录成功');
    }

    public function logout(Request $request)
    {
        $authorization = $request->header('Authorization');
        if ($authorization) {
            $authorizationArr = explode(' ', $authorization);
            if (count($authorizationArr) == 2) {
                $token = $authorizationArr[1];
                $this->userService->logout($token);
            }
        }
        return AdminResource::success(null, '登出成功');
    }

    public function refresh(Request $request)
    {
        $user = auth('admin')->user();
        $token = $this->userService->refreshToken($user, $request->get('scope', 'web'));

        return AdminResource::success([
            'data' => $token,
        ], '刷新成功');
    }

    public function user(Request $request)
    {
        $user = auth('admin')->user();
        return AdminResource::success([
            'homePath'  => '/workspace',
            'id'    =>  $user->id,
            'username'  => $user->username,
            'realName'  => $user->real_name ?? '用户',
            'roles' => [],
        ], '获取成功');
    }

    public function menus(Request $request)
    {
        $user = auth('admin')->user();
        return AdminResource::success($this->menuService->getAllMenusByUser($user));
    }
}
