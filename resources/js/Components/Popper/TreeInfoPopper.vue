<template>
  <!-- Options helper: -->

  <InfoPopper>
    <div>
      <div
        class="h-6 bg-gray-500 text-white font-bold px2 rounded mb4 text-center"
      >
        {{ options?.title }} Info
      </div>
      <div>
        <div
          class="flex flex-row items-center justify-left gap-0"
          v-if="options?.editable"
        >
          <div class="mr2"><strong>To rename</strong> item use:</div>
          <PencilIcon class="text-gray-500 w-4 h-4" />
        </div>
        <div class="flex flex-row items-center justify-left gap-0">
          <div class="mr2">
            <strong>To move</strong> item hold and move icons:
          </div>
          <FolderIcon class="text-gray-500 w-4" />,
          <DocumentTextIcon class="text-gray-500 w-4" />
        </div>
        <div
          class="flex flex-row items-center justify-left gap-3"
          v-if="options?.deleteable"
        >
          <div class="mr2"><strong>To delete</strong> item use:</div>
          <TrashIcon class="text-red-400 w-4 h-4" />
        </div>
      </div>
      <div class="italic mt2">
        For quick workflow folder files are loaded when the parent folder is
        opened.
      </div>
    </div>
  </InfoPopper>
</template>

<script setup>
import {
  PencilIcon,
  FolderIcon,
  DocumentTextIcon,
  TrashIcon,
} from '@heroicons/vue/24/solid'

import InfoPopper from '@/Components/Popper/InfoPopper.vue'
const props = defineProps(['options'])
</script>

<style lang="scss" scoped></style>
