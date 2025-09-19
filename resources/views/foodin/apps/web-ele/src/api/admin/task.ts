import { requestClient } from '#/api/request';

export async function downloadApi(id: number, type: string) {
  return requestClient.download<Blob>(`/tasks/${id}/download`, {
    params: {
      type,
    },
    headers: {
      'X-Need-Message': 'true',
    },
  });
}
