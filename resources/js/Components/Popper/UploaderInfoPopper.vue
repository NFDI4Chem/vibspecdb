<template>
  <InfoPopper>
    <div>
      <div
        class="mx-1 flex justify-center items-center h-6 bg-gray-500 text-white font-bold py-0 px2 rounded-0 mb4"
      >
        File Uploader Info
      </div>
      <div class="px2">
        File Uploader will upload files to the
        <strong>selected </strong>
        folder or dataset from the
        <em>Files Tree</em>.
      </div>
      <div class="mt2">
        <div class="px2">
          In case where no folder is selected, a root folder of the Study will
          be used.
        </div>
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
