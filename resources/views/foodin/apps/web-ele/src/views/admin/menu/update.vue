<script lang="ts" setup>
import { defineEmits } from 'vue';

import { useVbenModal } from '@vben/common-ui';

import { useVbenForm } from '#/adapter/form';
import * as menuApi from '#/api/admin/menu';

const emit = defineEmits();
let id = 0;

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
    menuApi
      .updateApi(id, form)
      .then(() => {
        emit('confirm');
        modalApi.close();
      })
      .catch((error) => {
        if (error.code === 422) {
          for (const key in error.data) {
            formApi.form.setFieldError(key, error.data[key]);
          }
        }
      });
  },
  onOpenChange(isOpen: boolean) {
    if (isOpen) {
      const values = modalApi.getData();
      if (values) {
        id = values.id;
        formApi.setValues(values);
      }
    }
  },
  title: '编辑菜单',
});

const [Form, formApi] = useVbenForm({
  schema: [
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入菜单名称',
      },
      fieldName: 'title',
      label: '菜单名称',
      rules: 'required',
    },
    {
      component: 'InputNumber',
      componentProps: {
        placeholder: '请输入排序',
      },
      fieldName: 'order',
      label: '排序',
    },
    {
      component: 'IconPicker',
      componentProps: {
        placeholder: '请选择菜单图标',
      },
      fieldName: 'icon',
      label: '图标',
    },
    {
      component: 'Switch',
      fieldName: 'hideInMenu',
      label: '隐藏菜单',
    },
    {
      component: 'Switch',
      fieldName: 'keepAlive',
      label: '缓存标签页',
    },
    {
      component: 'Switch',
      fieldName: 'affixTab',
      label: '固定标签页',
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
