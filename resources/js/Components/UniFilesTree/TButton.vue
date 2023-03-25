<template>
  <!-- <w-tooltip transition="bounce" top v-if="false">
    <template #activator="{ on }">
      <w-icon v-on="on" class="text-gray-500 w-5 active-field"
        >mdi {{ switchIcon }}</w-icon
      >
    </template>
    <div>{{ capitalizeFirstAlphabet(props?.type) }}</div>
  </w-tooltip> -->
  <w-icon v-on="on" class="text-gray-500 w-5 active-field"
    >mdi {{ switchIcon }}</w-icon
  >
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps(['type', 'open'])

const capitalizeFirstAlphabet = string =>
  string.substring(0, 1).toUpperCase() + string.substring(1)

const switchIcon = computed(() => {
  let icon = ''
  switch (props?.type) {
    case 'file':
      icon = 'mdi-file-document'
      break
    case 'directory':
      icon = props?.open ? 'mdi-folder-open' : 'mdi-folder'
      break
    case 'project':
      icon = props?.open ? 'mdi-folder-table-outline' : 'mdi-folder-table'
      break
    case 'study':
      icon = 'mdi-bookshelf'
      break
    default:
      break
  }
  return icon
})
</script>

<style lang="scss" scoped></style>
