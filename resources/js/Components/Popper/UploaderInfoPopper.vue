<template>
  <InfoPopper>
    <div>
      <div
        class="flex justify-center items-center h-6 bg-gray-500 text-white font-bold py-0 px2 rounded-0 mb4"
      >
        File Uploader Info
      </div>
      <div class="px2">
        File Uploader will upload files to the
        <strong>selected folder</strong>
        from the
        <em>Files Tree</em>.
      </div>
      <div class="mt2">
        <div class="px2">
          In case where no folder is selected, a root folder of the Study will
          be used with the Sign:
        </div>
        <strong
          class="flex flex-row justify-center items-center gap-2 bg-gray-100 mt2"
        >
          <CircleStackIcon class="h-5 w-5 text-gray-500" />/
        </strong>
      </div>
    </div>
  </InfoPopper>
</template>

<script setup>
import { InformationCircleIcon, CircleStackIcon } from '@heroicons/vue/24/solid'
import InfoPopper from '@/Components/Popper/InfoPopper.vue'
const props = defineProps(['selectTreeFolder'])
</script>

<style lang="scss" scoped></style>
