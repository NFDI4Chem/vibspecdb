<template>
  <app-layout :title="project?.name" :projects="projects">
    <template #header>
      <project-header :project="project" @toggleDetails="toggleDetails" />
      <project-details ref="projectDetailsElement" :project="project" />
    </template>
    <div>
      <studies :project="project" :studies="project?.studies" />
    </div>
  </app-layout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

import Studies from '@/Pages/Project/Studies/Index.vue'
import ProjectDetails from './Partials/Details.vue'
import ProjectHeader from './Partials/Elements/ProjectHeader.vue'

const props = defineProps(['project', 'projects'])
const projectDetailsElement = ref()

const toggleDetails = () => {
  projectDetailsElement.value.toggleDetails()
}
</script>
