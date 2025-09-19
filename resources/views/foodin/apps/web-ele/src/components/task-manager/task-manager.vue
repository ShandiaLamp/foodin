<script lang="ts" setup>
import type { VxeGridProps } from '#/adapter/vxe-table';

import { defineExpose } from 'vue';

import { useVbenModal } from '@vben/common-ui';
import { downloadFileFromBlob } from '@vben/utils';

import {
  ElButton,
  ElDropdown,
  ElDropdownItem,
  ElDropdownMenu,
  ElProgress,
  ElTag,
} from 'element-plus';

import { useVbenVxeGrid } from '#/adapter/vxe-table';
import * as taskApi from '#/api/admin/task';
import * as userApi from '#/api/core/user';

const [Modal, modalApi] = useVbenModal({
  fullscreenButton: true,
  onCancel() {
    modalApi.close();
  },
  onConfirm: async () => {},
  title: '任务管理',
});

interface RowType {
  id: number;
  title: string;
  type: string;
  status: number;
  progress: number;
  remarks: string;
  createdAt: string;
  meta: any;
}

const gridOptions: VxeGridProps<RowType> = {
  columns: [
    { field: 'id', title: 'ID', width: 50 },
    { field: 'title', title: '任务名称' },
    {
      field: 'status',
      title: '状态',
      width: 180,
      slots: { default: 'status' },
    },
    { field: 'remarks', title: '备注' },
    { field: 'createdAt', title: '创建时间' },
    {
      field: 'action',
      showOverflow: true,
      title: '操作',
      fixed: 'right',
      slots: { default: 'action' },
    },
  ],
  height: 'auto',
  keepSource: true,
  proxyConfig: {
    ajax: {
      query: async ({ page }) => {
        return await new Promise<{ items: any; total: number }>(
          (resolve, reject) => {
            userApi
              .getUserTasksApi({
                page: page.currentPage,
                pageSize: page.pageSize,
              })
              .then((result: any) => {
                resolve({
                  total: result.total,
                  items: result.data,
                });
              })
              .catch((error) => {
                reject(error);
              });
          },
        );
      },
    },
  },
  toolbarConfig: {
    refresh: true,
    zoom: true,
    // @ts-ignore
    search: true,
  },
};

const [Grid, gridApi] = useVbenVxeGrid({ gridOptions });

const open = () => {
  modalApi.open();
};

defineExpose({ open });

const onDownload = (id: number, type: string, path: string) => {
  const pathArr = path.split('/');
  const fileName =
    pathArr.length > 0 ? pathArr[pathArr.length - 1] : 'result.xlsx';
  gridApi.setLoading(true);
  taskApi
    .downloadApi(id, type)
    .then((res) => {
      gridApi.setLoading(false);
      downloadFileFromBlob({
        fileName,
        source: res,
      });
    })
    .catch(() => {
      gridApi.setLoading(true);
    });
};
</script>

<template>
  <Modal class="h-[75%] w-[50%]" :footer="false" :z-index="500">
    <Page auto-content-height>
      <Grid>
        <template #status="{ row }">
          <div class="flex items-center justify-center">
            <ElTag v-if="row.status === 0" size="small"> 待处理 </ElTag>
            <ElTag v-if="row.status === 1" type="primary" size="small">
              进行中
            </ElTag>

            <ElTag v-if="row.status === 2" type="success" size="small">
              成功
            </ElTag>
            <ElTag v-if="row.status === 3" type="danger" size="small">
              失败
            </ElTag>
            <ElTag v-if="row.status === 4" type="warning" size="small">
              验证失败
            </ElTag>
            <ElProgress
              v-if="row.status === 1"
              class="mx-1"
              type="circle"
              :percentage="row.progress"
              :show-text="false"
              size="small"
              :width="24"
            />
            <div v-if="row.status === 1">{{ row.progress }}%</div>
          </div>
        </template>
        <template #action="{ row }">
          <ElDropdown placement="bottom-end">
            <ElButton size="small">操作</ElButton>
            <template #dropdown>
              <ElDropdownMenu>
                <ElDropdownItem
                  v-if="
                    row.status === 2 && row.type === 'export' && row.meta?.path
                  "
                  @click="
                    () =>
                      onDownload(row.id, 'export_result_file', row.meta?.path)
                  "
                >
                  下载导出的结果表格
                </ElDropdownItem>
              </ElDropdownMenu>
            </template>
          </ElDropdown>
        </template>
      </Grid>
    </Page>
  </Modal>
</template>
