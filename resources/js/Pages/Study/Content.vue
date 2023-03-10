<template>
  <div>
    <study-layout :project="project" :study="study">
      <template #scontent>
        <div class="bg-white shadow-md flex flex-col flex-1 mb-0">
          <div class="px4 py-4 project-tabs w-full">
            <w-tabs :items="tabs" class="w-full rounded-none border-0">
              <template #item-content="{ index }">
                <Info v-if="index === 1" :item="study" type="study" />
                <UploadFiles
                  v-if="index === 2"
                  :study="study"
                  :project="project"
                  :files="files"
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

<script>
import StudyLayout from '@/Pages/Study/Layout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import {
  InformationCircleIcon,
  FolderOpenIcon,
  BriefcaseIcon,
  QueueListIcon,
  BeakerIcon,
  ClipboardDocumentListIcon,
  Cog6ToothIcon,
} from '@heroicons/vue/24/outline'
const subNavigation = [
  // { name: "About", route: "study", icon: InformationCircleIcon },
  { name: 'Upload Files', route: 'study.file-upload', icon: FolderOpenIcon },
  { name: 'Files', route: 'study.files', icon: ClipboardDocumentListIcon },
  { name: 'Select Model', route: 'study.models', icon: BeakerIcon },
  { name: 'Submit Job', route: 'study.submit-job', icon: QueueListIcon },
  { name: 'Jobs', route: 'study.jobs', icon: BriefcaseIcon },
]

export default {
  components: {
    StudyLayout,
    Link,
  },
  // props: ["study", "project", "current"],
  setup() {
    return {
      subNavigation,
    }
  },
}
</script>

<script setup>
import { ref } from 'vue'

import Info from '@/Pages/Study/Partials/Tabs/Info.vue'
import UploadFiles from '@/Pages/Study/Partials/Tabs/UploadFiles.vue'

const props = defineProps(['study', 'project', 'current', 'files'])

const tabs = [
  { title: 'Info', content: 'Info and metadata.' },
  { title: 'Upload', content: 'Files Tree and uploader.' },
  { title: 'Files', content: 'Files Tree and preview.' },
]
</script>

<style lang="scss">
.project-tabs {
  .w-tabs__bar-item {
    margin-right: 10px;
    width: 80px;
  }
  .w-tabs__content {
    padding-left: 0;
    padding-right: 0;
  }
}
</style>
