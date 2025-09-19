<script lang="ts" setup>
import type { VbenFormProps } from '#/adapter/form';
import type { VxeGridProps } from '#/adapter/vxe-table';

import { Page, useVbenModal } from '@vben/common-ui';

import { ElButton, ElPopconfirm, ElTag } from 'element-plus';

import { useVbenVxeGrid } from '#/adapter/vxe-table';
import * as userApi from '#/api/admin/user';

import createModal from './create.vue';
import updateModal from './update.vue';

interface RowType {
  id: number;
  username: string;
  realName: string;
  super: boolean;
  enabled: number;
  roles: Array<any>;
  dataPermission: null | Record<string, Array<number>>;
  createdAt: string;
}

const gridOptions: VxeGridProps<RowType> = {
  columns: [
    { field: 'id', title: 'ID', width: 50 },
    { field: 'username', title: '用户名' },
    { field: 'realName', title: '真实名' },
    { field: 'roles', title: '角色', slots: { default: 'roles' } },
    { field: 'enabled', title: '启用状态', slots: { default: 'enabled' } },
    { field: 'createdAt', width: 150, title: '创建时间' },
    {
      field: 'action',
      showOverflow: true,
      title: '操作',
      width: 450,
      fixed: 'right',
      slots: { default: 'action' },
    },
  ],
  height: 'auto',
  keepSource: true,
  proxyConfig: {
    ajax: {
      query: async ({ page }, formValues) => {
        return await new Promise<{ items: any; total: number }>(
          (resolve, reject) => {
            userApi
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
        placeholder: '请输入用户名',
      },
      defaultValue: '',
      fieldName: 'username',
      label: '用户名',
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
const [CreateModal, createModalApi] = useVbenModal({
  connectedComponent: createModal,
});
const [UpdateModal, updateModalApi] = useVbenModal({
  connectedComponent: updateModal,
});

const onUpdateEnabled = (id: number, enabled: boolean) => {
  gridApi.setLoading(true);
  userApi
    .updateEnabledApi(id, {
      enabled,
    })
    .then(() => {
      gridApi.query();
      gridApi.setLoading(false);
    })
    .catch(() => {
      gridApi.setLoading(false);
    });
};

const onDelete = (id: number) => {
  gridApi.setLoading(true);
  userApi
    .deleteApi(id)
    .then(() => {
      gridApi.query();
      gridApi.setLoading(false);
    })
    .catch(() => {
      gridApi.setLoading(false);
    });
};
</script>

<template>
  <Page auto-content-height>
    <Grid grid-class="size--small is--animat is--round p-2">
      <template #toolbar-tools>
        <ElButton
          class="mr-2"
          type="primary"
          @click="() => createModalApi.open()"
        >
          <span class="icon-[mdi--plus]"></span> 创建用户
        </ElButton>
      </template>

      <template #enabled="{ row }">
        <ElTag v-if="row.enabled" type="success">启用</ElTag>
        <ElTag v-else>禁用</ElTag>
      </template>

      <template #roles="{ row }">
        <ElTag
          class="m-1"
          type="primary"
          v-for="(role, i) in row.roles"
          :key="i"
        >
          {{ role.name }}
        </ElTag>
      </template>

      <template #action="{ row }">
        <ElButton
          size="small"
          v-if="row.enabled"
          @click="onUpdateEnabled(row.id, false)"
        >
          禁用
        </ElButton>
        <ElButton
          size="small"
          type="success"
          v-else
          @click="onUpdateEnabled(row.id, true)"
        >
          启用
        </ElButton>

        <ElButton
          size="small"
          type="primary"
          @click="
            updateModalApi
              .setData({
                ...row,
                roleIds: row.roles.map((item) => item.id),
              })
              .open()
          "
        >
          编辑
        </ElButton>
        <ElPopconfirm title="确定删除吗？" @confirm="onDelete(row.id)">
          <template #reference>
            <ElButton size="small" type="danger" :disabled="row.super">
              删除
            </ElButton>
          </template>
        </ElPopconfirm>
      </template>
    </Grid>

    <CreateModal @confirm="gridApi.query()" />
    <UpdateModal @confirm="gridApi.query()" />
  </Page>
</template>
