<template>
  <div
    class="h-full w-full p-2 overflow-auto flex flex-col justify-between text-sm"
  >
    <div class="flex flex-col gap-1" v-if="data_keys?.length">
      <div v-for="(item, idx) in data_keys" :key="idx">
        <div class="px-1 bg-white flex flex-row justify-between">
          <div class="mr2 text-bold text-sky-800">{{ metatypes?.[item] }}</div>
          <div>{{ metadata?.[item] }}</div>
        </div>
      </div>
    </div>
    <div v-else class="text-red-400">There is no metadata</div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { metatypes } from '@/VueComposable/useMeta'

const props = defineProps(['data'])

const hide_keys = ['path', 'filename']

const metadata = computed(() => {
  return props?.data?.metadata
})

const data_keys = computed(() => {
  return Object.keys(metadata.value)
    .filter(f => !hide_keys.includes(f))
    .reverse()
})
</script>

<style lang="scss" scoped></style>
