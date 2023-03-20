<template>
  <div class="bg-transparent">
    <div class="mx-auto max-w-2xl py-2 sm:py-4 lg:max-w-7xl">
      <h2 class="sr-only">Projects</h2>

      <div class="flex flex-row flex-wrap gap-x-12 gap-y-8">
        <ProjectItem
          class=""
          v-for="item in items"
          :key="`project_${item?.uuid}`"
          :item="item"
          @onSelect="onSelect"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import ProjectItem from '@/Pages/Dashboard/Projects/ProjectItem.vue'

const props = defineProps(['items'])
const emit = defineEmits(['onSelect'])

const onSelect = item => {
  emit('onSelect', item)
}
</script>

<style lang="scss" scoped></style>
