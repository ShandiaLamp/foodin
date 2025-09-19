<script lang="ts" setup>
import { defineEmits, ref } from 'vue';

import { useVbenModal } from '@vben/common-ui';

import { useVbenForm } from '#/adapter/form';
import * as roleApi from '#/api/admin/role';
import * as userApi from '#/api/admin/user';

const emit = defineEmits<{
  confirm: [];
}>();

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
    userApi
      .storeApi(form)
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
      const { values } = modalApi.getData<Record<string, any>>();
      if (values) {
        formApi.setValues(values);
      }
    }
  },
  title: '创建用户',
});

const fetching = ref(false);
const [Form, formApi] = useVbenForm({
  schema: [
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入用户名',
      },
      fieldName: 'username',
      label: '用户名',
      rules: 'required',
    },
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入密码',
        type: 'password',
      },
      fieldName: 'password',
      label: '密码',
      rules: 'required',
    },
    {
      component: 'Input',
      componentProps: {
        placeholder: '请输入真实名',
      },
      fieldName: 'realName',
      label: '真实名',
    },
    {
      component: 'ApiSelect',
      componentProps: {
        clearable: true,
        filterable: true,
        options: [],
        placeholder: '请选择角色',
        multiple: true,
        api: () => {
          return roleApi.optionsApi().then((result) => {
            return result;
          });
        },
        notFoundContent: fetching.value ? undefined : null,
      },
      fieldName: 'roleIds',
      label: '角色',
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
