import { requestClient } from '#/api/request';

/**
 * 查询角色列表
 */
export async function listApi(data: any) {
  return requestClient.get<any>('/roles', {
    params: data,
  });
}

/**
 * 创建角色
 */
export async function storeApi(data: any) {
  return requestClient.post<any>('/roles', data);
}

/**
 * 创建角色
 */
export async function updateApi(id: number, data: any) {
  return requestClient.put<any>(`/roles/${id}`, data);
}

export async function deleteApi(id: number) {
  return requestClient.delete<null>(`/roles/${id}`);
}

/**
 * 修改角色是否启用
 */
export async function updateEnabledApi(id: number, data: any) {
  return requestClient.put<any>(`/roles/${id}/enabled`, data);
}

export async function exportApi(data: any) {
  return requestClient.post<null>(`/roles/export`, data, {
    headers: {
      'X-Need-Message': 'true',
    },
  });
}

export async function optionsApi() {
  return requestClient.get<any>('/roles/options');
}
