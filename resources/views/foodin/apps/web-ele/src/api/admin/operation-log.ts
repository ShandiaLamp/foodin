import { requestClient } from '#/api/request';

/**
 * 查询操作日志列表
 */
export async function listApi(data: any) {
  return requestClient.get<any>('/operation-logs', {
    params: data,
  });
}

export async function exportApi(data: any) {
  return requestClient.post<null>(`/operation-logs/export`, data, {
    headers: {
      'X-Need-Message': 'true',
    },
  });
}
