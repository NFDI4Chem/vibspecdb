<template>
  <div>
    <study-layout :project="project" :study="study" :projects="projects">
      <template #scontent>
        <div class="bg-white shadow-md flex flex-col flex-1 mb-0">
          <div class="px4 py-4 project-tabs w-full">
            <SplitterToggler v-model:slit_views="slit_views" />
            <w-tabs
              :items="tabs"
              class="w-full rounded-none border-0"
              v-model="tab"
            >
              <template #item-content="{ index }">
                <Info v-if="index === 1" :item="study" type="study" />
                <Metadata v-if="index === 2" :item="study" type="study" />
                <UploadFiles
                  v-if="index === 3"
                  :study="study"
                  :project="project"
                  :files="files"
                  v-model:slit_views="slit_views"
                />
              </template>
            </w-tabs>
          </div>
          <slot name="study-section"></slot>
        </div>
      </template>
    </study-layout>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'

import StudyLayout from '@/Pages/Study/Layout.vue'
import Info from '@/Pages/Study/Partials/Tabs/Info.vue'
import SplitterToggler from '@/Pages/Study/Partials/Elements/SplitterToggler.vue'
import Metadata from '@/Pages/Study/Partials/Tabs/Metadata.vue'
import UploadFiles from '@/Pages/Study/Partials/Tabs/UploadFiles.vue'

const props = defineProps(['study', 'project', 'files', 'tab', 'projects'])

const tab = computed(() => {
  let t = props?.tab
  if (t < 1 || t > tabs.length) t = 1
  return t - 1
})

const tabs = [
  { title: 'Info', content: 'Study Common Info.' },
  { title: 'Metadata', content: 'Custom Metadata.' },
  { title: 'Upload', content: 'Files Tree and uploader.' },
  { title: 'Files', content: 'Files Tree and preview.' },
]

const slit_views = ref({
  top_visible: false,
  left_visible: true,
  right_visible: true,
})
</script>

<style lang="scss">
.project-tabs {
  .w-tabs__bar-item {
    margin-right: 10px;
    width: 90px;
    height: 30px;
  }
  .w-tabs__content {
    padding-left: 0;
    padding-right: 0;
  }
}
</style>
