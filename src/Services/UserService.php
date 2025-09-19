<?php

namespace Shandialamp\Foodin\Services;

use Auth;
use DB;
use Hash;
use Shandialamp\Foodin\Exceptions\ServiceException;
use Shandialamp\Foodin\Models\User;
use Shandialamp\Foodin\Models\UserToken;

class UserService
{
    /**
     * 用户数量
     * @return int
     */
    public function count(): int
    {
        return User::count();
    }

    /**
     * 创建用户
     * @param mixed $username 用户名
     * @param mixed $password 密码
     * @param mixed $mobile 手机
     * @param mixed $realName 真实名
     * @return User
     */
    public function create($username, $password, $mobile, $realName = null):User
    {
        return User::create([
            'username'  => $username,
            'password'  => Hash::make($password),
            'mobile'    => $mobile,
            'real_name' => $realName,
        ]);
    }

    /**
     * 登录
     * @param mixed $username
     * @param mixed $password
     * @param mixed $scope
     * @throws \Shandialamp\Foodin\Exceptions\ServiceException
     * @return \Shandialamp\Foodin\Models\UserToken
     */
    public function login($username, $password, $scope = 'web'): UserToken
    {
        $token = Auth::guard('admin')->attempt([
            'username'  => $username,
            'password'  => $password
        ]);
        if ($token === false) {
            throw new ServiceException('用户名或者密码错误');
        }
        $user = Auth::guard('admin')->user();

        $ttlMinutes = Auth::guard('admin')->factory()->getTTL();
        $expiresAt = now()->addMinutes($ttlMinutes);
        
        DB::beginTransaction();
        $user->tokens()->where('scope', $scope)->delete();

        $userToken = $user->tokens()->create([
            'token' => $token,
            'scope' => $scope,
            'expire_at' => $expiresAt,
        ]);
        DB::commit();
        return $userToken;
    }
}
