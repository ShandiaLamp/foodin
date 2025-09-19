import { requestClient } from '#/api/request';

export async function treeApi() {
  return requestClient.get<Array<any>>('/menus');
}

export async function updateApi(id: number, data: any) {
  return requestClient.put<any>(`/menus/${id}`, data);
}

export async function updateRoutesApi(id: number, data: any) {
  return requestClient.put<any>(`/menus/${id}/routes`, data);
}
