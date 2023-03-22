<template>
  <div class="bg-transparent">
    <div class="mx-auto max-w-2xl py-2 sm:py-4 lg:max-w-7xl">
      <h2 class="sr-only">Studies</h2>

      <div class="flex flex-row flex-wrap gap-x-12 gap-y-8 justify-center">
        <StudyItem
          v-for="item in items"
          :key="`study_${item?.uuid}`"
          :item="item"
          @onSelect="onSelect"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import StudyItem from '@/Pages/Project/Studies/StudyItem.vue'

const props = defineProps(['items'])
const emit = defineEmits(['onSelect'])

const onSelect = item => {
  emit('onSelect', item)
}
</script>

<style lang="scss" scoped></style>
