<template>
  <div class="bg-transparent">
    <div class="mx-auto max-w-2xl py-2 sm:py-4 lg:max-w-7xl">
      <h2 class="sr-only">Studies</h2>

      <div
        class="grid grid-cols-1 gap-y-2 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8 2xl:grid-cols-4 3xl:grid-cols-5"
      >
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
import StudyItem from '@/Pages/Study/Index/StudyItem.vue'

const props = defineProps(['items'])
const emit = defineEmits(['onSelect'])

const onSelect = item => {
  emit('onSelect', item)
}
</script>

<style lang="scss" scoped></style>
