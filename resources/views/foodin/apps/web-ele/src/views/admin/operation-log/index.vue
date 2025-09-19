<script lang="ts" setup>
import type { VbenFormProps } from '#/adapter/form';
import type { VxeGridProps } from '#/adapter/vxe-table';

import { JsonViewer, Page, useVbenModal } from '@vben/common-ui';

import { ElButton } from 'element-plus';

import { useVbenVxeGrid } from '#/adapter/vxe-table';
import * as operationLogApi from '#/api/admin/operation-log';

import exportModal from './export.vue';

interface RowType {
  id: number;
  title: string;
  username: string;
  method: string;
  uri: string;
  ip: string;
  duration: number;
  raw: any;
  createdAt: string;
}

const gridOptions: VxeGridProps<RowType> = {
  columns: [
    { field: 'id', title: 'ID', width: 50 },
    { field: 'title', title: '标题' },
    { field: 'username', title: '用户' },
    { field: 'method', title: '请求方法' },
    { field: 'uri', title: '请求地址' },
    { field: 'ip', title: 'IP' },
    { field: 'createdAt', title: '创建时间' },
    { title: '详情', type: 'expand', width: 80, slots: { content: 'expand' } },
  ],
  height: 'auto',
  keepSource: true,
  proxyConfig: {
    ajax: {
      query: async ({ page }, formValues) => {
        return await new Promise<{ items: any; total: number }>(
          (resolve, reject) => {
            operationLogApi
              .listApi({
                page: page.currentPage,
                pageSize: page.pageSize,
                ...formValues,
              })
              .then((result: any) => {
                resolve({
                  total: result.total,
                  items: result.data,
                });
              })
              .catch((error: any) => {
                reject(error);
              });
          },
        );
      },
    },
  },
  toolbarConfig: {
    refresh: { code: 'query' },
    zoom: true,
    // @ts-ignore
    search: true,
  },
};

const formOptions: VbenFormProps = {
  // 默认展开
  collapsed: false,
  schema: [
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入标题',
      },
      fieldName: 'title',
      label: '标题',
    },
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入用户',
      },
      fieldName: 'username',
      label: '用户',
    },
  ],
  // 控制表单是否显示折叠按钮
  showCollapseButton: false,
  submitButtonOptions: {
    content: '查询',
  },
  // 是否在字段值改变时提交表单
  submitOnChange: false,
  // 按下回车时是否提交表单
  submitOnEnter: false,
};

const [Grid, gridApi] = useVbenVxeGrid({ formOptions, gridOptions });
const [ExportModal, exportModalApi] = useVbenModal({
  connectedComponent: exportModal,
});

const onExport = async () => {
  const query = await gridApi.formApi.getValues();
  exportModalApi
    .setData({
      query,
    })
    .open();
};
</script>

<template>
  <Page auto-content-height>
    <Grid grid-class="size--small is--animat is--round p-2">
      <template #toolbar-tools>
        <ElButton class="mr-2" @click="onExport">
          <span class="icon-[mdi--plus]"></span> 导出
        </ElButton>
      </template>

      <template #expand="{ row }">
        <div class="p-4">
          <div class="flex">
            <label>请求参数</label>
            <pre>{{ row.raw?.query }}</pre>
          </div>
          <div class="flex">
            <label>请求正文：</label>
            <JsonViewer
              :value="row.raw?.reqBody ? JSON.parse(row.raw?.reqBody) : {}"
            />
          </div>
          <div class="flex">
            <label>响应状态码：</label>
            <div>{{ row.raw?.respStatusCode }}</div>
          </div>
          <div>
            <label>响应正文：</label>
            <JsonViewer
              :value="row.raw?.respBody ? JSON.parse(row.raw?.respBody) : null"
            />
          </div>
        </div>
      </template>
    </Grid>

    <ExportModal />
  </Page>
</template>
