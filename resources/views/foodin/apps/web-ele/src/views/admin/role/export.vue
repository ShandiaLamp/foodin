<script lang="ts" setup>
import type { Ref } from 'vue';

import { inject } from 'vue';

import { useVbenModal } from '@vben/common-ui';

import { useVbenForm } from '#/adapter/form';
import * as roleApi from '#/api/admin/role';
import { TaskManager } from '#/components/task-manager';

const tm = inject<Ref<InstanceType<typeof TaskManager> | null>>('tm');

let query: any = {};
const [Modal, modalApi] = useVbenModal({
  fullscreenButton: true,
  onCancel() {
    modalApi.close();
  },
  onConfirm: async () => {
    const v = await formApi.validate();
    if (!v.valid) {
      return;
    }
    const form = await formApi.getValues();
    modalApi.lock();
    roleApi
      .exportApi({
        ...form,
        query,
      })
      .then(() => {
        modalApi.unlock();
        modalApi.close();
        tm.value?.open();
      })
      .catch(() => {
        modalApi.unlock();
      });
  },
  onOpenChange(isOpen: boolean) {
    if (isOpen) {
      const { values } = modalApi.getData<Record<string, any>>();
      if (values && values.query) {
        query = values.query;
      }
    }
  },
  title: '导出角色',
});

const [Form, formApi] = useVbenForm({
  schema: [
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入备注',
      },
      fieldName: 'remarks',
      label: '备注',
    },
  ],
  showDefaultActions: false,
});
</script>

<template>
  <Modal>
    <Form />
  </Modal>
</template>
