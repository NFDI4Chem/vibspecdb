<template>
  <app-layout :title="study?.name">
    <template #header>
      <study-header
        :study="study"
        :project="project"
        @toggleDetails="toggleDetails"
      />
      <study-details
        ref="studyDetailsElement"
        :study="study"
        :project="project"
      />
    </template>
    <div class="px-0 sm:px-0 flex flex-1 flex-col">
      <slot name="scontent"></slot>
    </div>
  </app-layout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

import StudyDetails from './Partials/Details.vue'
import StudyHeader from './Partials/Elements/StudyHeader.vue'

const props = defineProps(['study', 'project'])
const studyDetailsElement = ref()

const toggleDetails = () => {
  studyDetailsElement.value.toggleDetails()
}
</script>
