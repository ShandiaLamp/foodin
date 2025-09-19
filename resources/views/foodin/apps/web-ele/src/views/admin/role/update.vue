<script lang="ts" setup>
import { defineEmits } from 'vue';

import { useVbenModal } from '@vben/common-ui';

import { useVbenForm } from '#/adapter/form';
import * as roleApi from '#/api/admin/role';

const emit = defineEmits<{
  confirm: [];
}>();
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
    modalApi.lock();
    roleApi
      .updateApi(id, form)
      .then(() => {
        emit('confirm');
        modalApi.unlock();
        modalApi.close();
      })
      .catch((error) => {
        modalApi.unlock();
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
  title: '编辑角色',
});

const [Form, formApi] = useVbenForm({
  schema: [
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入角色名称',
      },
      fieldName: 'name',
      label: '角色名称',
      rules: 'required',
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
