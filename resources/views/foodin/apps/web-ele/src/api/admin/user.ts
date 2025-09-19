import { requestClient } from '#/api/request';

/**
 * 查询用户列表
 */
export async function listApi(data: any) {
  return requestClient.get<any>('/users', {
    params: data,
  });
}

/**
 * 创建用户
 */
export async function storeApi(data: any) {
  return requestClient.post<any>('/users', data);
}

/**
 * 创建用户
 */
export async function updateApi(id: number, data: any) {
  return requestClient.put<any>(`/users/${id}`, data);
}

export async function deleteApi(id: number) {
  return requestClient.delete<null>(`/users/${id}`);
}

/**
 * 修改用户是否启用
 */
export async function updateEnabledApi(id: number, data: any) {
  return requestClient.put<any>(`/users/${id}/enabled`, data);
}
