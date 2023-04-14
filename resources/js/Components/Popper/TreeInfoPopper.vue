<template>
  <!-- Options helper: -->

  <InfoPopper>
    <div>
      <div
        class="mx-1 h-6 bg-gray-500 text-white font-bold px2 rounded-0 mb4 text-center"
      >
        {{ options?.title }} Info
      </div>
      <div>
        <div
          class="flex flex-row items-center justify-left gap-0"
          v-if="options?.editable"
        >
          <div class="mr2"><strong>To rename</strong> item use:</div>
          <w-icon class="text-gray-500 w-4 h-4">mdi mdi-rename</w-icon>
        </div>
        <div class="flex flex-row items-center justify-left gap-0">
          <div class="mr2"><strong>Рold and move</strong>with icons:</div>

          <w-icon class="text-gray-500 w-4 h-4 mx1">mdi mdi-folder</w-icon> |
          <w-icon class="text-gray-500 w-4 h-4 mx1"
            >mdi mdi-file-document</w-icon
          >
          |
          <w-icon class="text-gray-500 w-4 h-4 mx1">mdi mdi-database</w-icon>
        </div>
        <div
          class="flex flex-row items-center justify-left gap-3"
          v-if="options?.deleteable"
        >
          <div class="mr2"><strong>To delete</strong> item use:</div>
          <w-icon class="text-red-400 w-4 h-4">mdi mdi-delete</w-icon>
        </div>
        <div class="italic">
          (Delete is destructive operation and can not be undone)
        </div>
      </div>
      <div class="italic mt2" v-if="false">
        For quick workflow folder files are loaded when the parent folder is
        opened.
      </div>
    </div>
  </InfoPopper>
</template>

<script setup>
import { TrashIcon } from '@heroicons/vue/24/solid'

import InfoPopper from '@/Components/Popper/InfoPopper.vue'
const props = defineProps(['options'])
</script>

<style lang="scss" scoped></style>
