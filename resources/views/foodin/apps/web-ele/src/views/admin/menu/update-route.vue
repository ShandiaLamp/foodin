<script lang="ts" setup>
import { defineEmits, ref } from 'vue';

import { useVbenModal } from '@vben/common-ui';

import * as menuApi from '#/api/admin/menu';
import { RouteSelect } from '#/components/route-select';

const emit = defineEmits<{
  confirm: [];
}>();
let id = 0;
const selectedRoutes = ref<Array<any>>([]);

const [Modal, modalApi] = useVbenModal({
  fullscreenButton: true,
  onCancel() {
    modalApi.close();
  },
  onConfirm: async () => {
    menuApi
      .updateRoutesApi(id, {
        routes: selectedRoutes.value,
      })
      .then(() => {
        emit('confirm');
        modalApi.close();
      })
      .catch(() => {});
  },
  onOpenChange(isOpen: boolean) {
    if (isOpen) {
      const values = modalApi.getData();
      console.log(values);
      if (values) {
        id = values.id;
        selectedRoutes.value = values.routes === null ? [] : values.routes;
      }
    }
  },
  title: '编辑菜单路由',
});
</script>

<template>
  <Modal class="w-[75%]">
    <RouteSelect v-model="selectedRoutes" />
  </Modal>
</template>
