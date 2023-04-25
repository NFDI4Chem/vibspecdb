<template>
  <div>
    <study-layout :project="project" :study="study" :projects="projects">
      <template #scontent>
        <div class="bg-white shadow-md flex flex-col flex-1 mb-0">
          <div class="px4 py-4 project-tabs w-full">
            <SplitterToggler
              v-if="StudyTab === 0"
              v-model:split_views="split_views"
            />
            <w-tabs
              :items="tabs"
              class="w-full rounded-none border-0"
              v-model="StudyTab"
            >
              <template #item-content="{ index }">
                <UploadFiles
                  v-if="index === 1"
                  :study="study"
                  :project="project"
                  :files="files"
                />
                <Info v-if="index === 2" :item="study" type="study" />
                <Metadata v-if="index === 3" :item="study" type="study" />
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

import { split_views } from '@/VueComposable/useStudyLayer'
import { StudyTab } from '@/VueComposable/useStudy'

const props = defineProps(['study', 'project', 'files', 'tab', 'projects'])

const tabs = [
  { title: 'Workspace', content: 'Files Tree and uploader.' },
  { title: 'Common Info', content: 'Study Common Info.' },
  { title: 'Study Metadata', content: 'Custom Metadata.' },
  // { title: 'Files', content: 'Files Tree and preview.' },
]

const onMountedTab = () => {
  let t = props?.tab
  if (t < 1 || t > tabs.length) t = 1
  return t - 1
}

StudyTab.value = onMountedTab()
</script>

<style lang="scss">
.project-tabs {
  .w-tabs__bar-item {
    margin-right: 10px;
    width: 140px;
    height: 35px;
  }
  .w-tabs__content {
    padding-left: 0;
    padding-right: 0;
  }
}
</style>
