<template>
  <div
    class="w-7 h-6 flex flex-col gap-1 absolute right-2 top-24 cursor-pointer z-1"
  >
    <div
      class="w-7 h-3"
      :class="{
        'bg-gray-400 hover:bg-gray-500': !split_views.top_visible,
        'bg-gray-500 hover:bg-gray-600': split_views.top_visible,
      }"
      @click="change({ top_visible: !split_views.top_visible })"
    />
    <div class="flex flex-row gap-1 w-6.5 h-2">
      <div
        class="w-3 h-3"
        :class="{
          'bg-gray-400 hover:bg-gray-500': !split_views.left_visible,
          'bg-gray-500 hover:bg-gray-600': split_views.left_visible,
        }"
        @click="
          change({
            left_visible: !split_views.left_visible,
            // right_visible: split_views.left_visible,
          })
        "
      />
      <div
        class="w-3 h-3"
        :class="{
          'bg-gray-400 hover:bg-gray-500': !split_views.right_visible,
          'bg-gray-500 hover:bg-gray-600': split_views.right_visible,
        }"
        @click="
          change({
            right_visible: !split_views.right_visible,
            // left_visible: split_views.right_visible,
          })
        "
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps(['split_views'])
const emit = defineEmits(['update:split_views'])

const change = changes => {
  emit('update:split_views', {
    ...props.split_views,
    ...changes,
  })
}
</script>

<style lang="scss" scoped></style>
