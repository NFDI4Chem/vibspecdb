<template>
  <div
    class="w-7 h-6 flex flex-col gap-1 absolute right-2 top-24 cursor-pointer z-10"
  >
    <div
      class="w-7 h-3"
      :class="{
        'bg-gray-400 hover:bg-gray-500': !slit_views.top_visible,
        'bg-gray-500 hover:bg-gray-600': slit_views.top_visible,
      }"
      @click="change({ top_visible: !slit_views.top_visible })"
    />
    <div class="flex flex-row gap-1 w-6.5 h-2">
      <div
        class="w-3 h-3"
        :class="{
          'bg-gray-400 hover:bg-gray-500': !slit_views.left_visible,
          'bg-gray-500 hover:bg-gray-600': slit_views.left_visible,
        }"
        @click="
          change({
            left_visible: !slit_views.left_visible,
            // right_visible: slit_views.left_visible,
          })
        "
      />
      <div
        class="w-3 h-3"
        :class="{
          'bg-gray-400 hover:bg-gray-500': !slit_views.right_visible,
          'bg-gray-500 hover:bg-gray-600': slit_views.right_visible,
        }"
        @click="
          change({
            right_visible: !slit_views.right_visible,
            // left_visible: slit_views.right_visible,
          })
        "
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps(['slit_views'])
const emit = defineEmits(['update:slit_views'])

const change = changes => {
  emit('update:slit_views', {
    ...props.slit_views,
    ...changes,
  })
}
</script>

<style lang="scss" scoped></style>
