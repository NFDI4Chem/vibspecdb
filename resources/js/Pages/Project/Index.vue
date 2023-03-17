<template>
  <div class="bg-white shadow-md flex flex-col flex-1 mb-0">
    <div class="px4 py-4 project-tabs w-full">
      <w-tabs :items="tabs" class="w-full rounded-none border-0" v-model="tab">
        <template #item-content="{ index }">
          <div>adsfasdf</div>
          <!-- <Info v-if="index === 1" :item="study" type="study" />
                <Metadata v-if="index === 2" :item="study" type="study" />
                <UploadFiles
                  v-if="index === 3"
                  :study="study"
                  :project="project"
                  :files="files"
                /> -->
        </template>
      </w-tabs>
    </div>
    <slot name="study-section"></slot>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps(['tab'])

const tab = computed(() => {
  let t = props?.tab
  if (t < 1 || t > tabs.length) t = 1
  return t - 1
})

const tabs = [
  { title: 'Studies', content: 'List of Study' },
  { title: 'Metadata', content: 'Custom Metadata.' },
  { title: 'Upload', content: 'Files Tree and uploader.' },
  { title: 'Files', content: 'Files Tree and preview.' },
]
</script>

<style lang="scss" scoped></style>
