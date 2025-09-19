import type { UserInfo } from '@vben/types';

import { requestClient } from '#/api/request';

/**
 * 获取用户信息
 */
export async function getUserInfoApi() {
  return requestClient.get<UserInfo>('/auth/user');
}

export async function getUserTasksApi(params: any) {
  return requestClient.get<any>('/auth/tasks', {
    params,
  });
}

export async function getUserRoutesApi() {
  return requestClient.get<any>('/auth/routes');
}
