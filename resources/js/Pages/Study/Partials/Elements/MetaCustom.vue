<template>
  <div class="metadata-wrapper">
    <div v-for="(meta, idx) in metadata" :key="meta.key">
      <div class="flex flex-row gap-6 py4">
        <meta-info-element v-model:meta="metadata[idx]" class="w-full" />
        <div class="mt-auto w-[20px]" @click="deleteRow(idx)">
          <w-button
            class="text-gray-600"
            sm
            bg-color="error-light3"
            icon="wi-cross"
            :disabled="disabled"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import MetaInfoElement from '@/Pages/Study/Partials/Elements/MetaInfoElement.vue'

const props = defineProps(['metadata', 'disabled'])
const emit = defineEmits(['metadata:update'])

const deleteRow = idx => {
  emit('metadata:update', props?.metadata.splice(idx, idx !== -1 ? 1 : 0))
}
</script>

<style lang="scss" scoped></style>
