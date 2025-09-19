<script lang="ts" setup>
import type { VxeGridProps } from '#/adapter/vxe-table';

import { Page, useVbenModal } from '@vben/common-ui';

import { Icon } from '@iconify/vue';
import { ElButton } from 'element-plus';

import { useVbenVxeGrid } from '#/adapter/vxe-table';
import * as menuApi from '#/api/core/menu';

import updateRouteModal from './update-route.vue';
import updateModal from './update.vue';

interface RowType {
  id: number;
  name: string;
  path: string;
  redirect: string;
  component: string;
  meta: any;
  routes: any;
  createdAt: string;
  children?: RowType[];
}

const gridOptions: VxeGridProps<RowType> = {
  columns: [
    { field: 'id', title: 'ID', width: 50 },
    // { field: 'name', title: '标识',  },
    { field: 'meta.title', title: '名称', treeNode: true },
    { field: 'path', title: '地址' },
    { field: 'redirect', title: '重定向' },
    // { field: 'component', title: '组件' },
    {
      field: 'meta.hideInMenu',
      title: '隐藏菜单',
      slots: { default: 'hideInMenu' },
    },
    { field: 'meta.order', title: '排序', sortable: true },
    { field: 'meta.icon', title: '图标', slots: { default: 'icon' } },
    {
      field: 'meta.keepAlive',
      title: '缓存标签页',
      slots: { default: 'keepAlive' },
    },
    {
      field: 'meta.affixTab',
      title: '固定标签页',
      slots: { default: 'affixTab' },
    },
    {
      field: 'action',
      showOverflow: true,
      width: 220,
      title: '操作',
      fixed: 'right',
      slots: { default: 'action' },
    },
  ],
  treeConfig: {
    showLine: true,
    // transform: true,
    rowField: 'id',
    childrenField: 'children',
    expandAll: true,
  },
  height: 'auto',
  keepSource: true,
  proxyConfig: {
    ajax: {
      query: async () => {
        return await new Promise<{ items: any; total: number }>(
          (resolve, reject) => {
            menuApi
              .getAllMenusApi()
              .then((result: any) => {
                resolve({
                  total: result.length,
                  items: result,
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

const [Grid, gridApi] = useVbenVxeGrid({ gridOptions });

const [UpdateModal, updateModalApi] = useVbenModal({
  connectedComponent: updateModal,
});
const [UpdateRouteModal, updateRouteModalApi] = useVbenModal({
  connectedComponent: updateRouteModal,
});
</script>

<template>
  <Page auto-content-height>
    <Grid grid-class="size--small is--animat is--round p-2">
      <template #icon="{ row }">
        <div class="flex justify-center">
          <Icon :icon="row.meta.icon" width="20" height="20" />
        </div>
      </template>
      <template #hideInMenu="{ row }">
        <div class="flex justify-center">
          <Icon
            v-if="row.meta.hideInMenu"
            icon="ant-design:check-circle-filled"
            width="20"
            height="20"
            color="#52c41a"
          />
          <Icon
            v-else
            icon="ant-design:close-circle-filled"
            width="20"
            height="20"
            color="#ff4d4f"
          />
        </div>
      </template>
      <template #keepAlive="{ row }">
        <div class="flex justify-center">
          <Icon
            v-if="row.meta.keepAlive"
            icon="ant-design:check-circle-filled"
            width="20"
            height="20"
            color="#52c41a"
          />
          <Icon
            v-else
            icon="ant-design:close-circle-filled"
            width="20"
            height="20"
            color="#ff4d4f"
          />
        </div>
      </template>
      <template #affixTab="{ row }">
        <div class="flex justify-center">
          <Icon
            v-if="row.meta.affixTab"
            icon="ant-design:check-circle-filled"
            width="20"
            height="20"
            color="#52c41a"
          />
          <Icon
            v-else
            icon="ant-design:close-circle-filled"
            width="20"
            height="20"
            color="#ff4d4f"
          />
        </div>
      </template>
      <template #action="{ row }">
        <ElButton
          size="small"
          type="primary"
          @click="
            updateModalApi
              .setData({
                ...row.meta,
                id: row.id,
              })
              .open()
          "
        >
          编辑
        </ElButton>
        <ElButton
          size="small"
          type="primary"
          @click="updateRouteModalApi.setData(row).open()"
        >
          编辑路由
        </ElButton>
      </template>
      <template #pager><div></div> </template>
    </Grid>
    <UpdateModal
      @confirm="
        () => {
          gridApi.query().then(() => {
            gridApi.grid.setAllTreeExpand(true);
          });
        }
      "
    />
    <UpdateRouteModal />
  </Page>
</template>
