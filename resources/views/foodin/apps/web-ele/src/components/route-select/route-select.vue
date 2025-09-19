<script lang="ts" setup>
import { defineEmits, defineProps, onMounted, ref } from 'vue';

import { ElTransfer } from 'element-plus';

import * as userApi from '#/api/core/user';

const props = defineProps<{
  modelValue: Array<any>;
}>();

const emit = defineEmits(['update:modelValue']);

const allRoutes = ref<Array<any>>([]);

onMounted(() => {
  userApi.getUserRoutesApi().then((res: any) => {
    allRoutes.value = res.map((item: any) => {
      return {
        key: `${item?.method} ${item?.path}`,
        label: `${item?.method} ${item?.path} ${item?.name}`,
      };
    });
  });
});

const onChange = (value: Array<any>) => {
  emit('update:modelValue', value);
};
</script>

<template>
  <ElTransfer
    class="text-center"
    :model-value="props.modelValue"
    :data="allRoutes"
    :filterable="true"
    :titles="['所有路由', '已选择路由']"
    @change="onChange"
  />
</template>

<style scoped>
/* Vue3 <style scoped> 需要 ::v-deep */
::v-deep(.el-transfer-panel) {
  width: 360px; /* 单个面板宽度 */
}
</style>
