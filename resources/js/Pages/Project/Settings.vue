<template>
  <app-layout :title="project.name">
    <template #header>
      <w-breadcrumbs
        icon="mdi mdi-arrow-right"
        :items="items"
        color="light-blue-dark3"
      />
    </template>
    <div class="py-12 px-10">
      <div>
        <project-delete :project="project"></project-delete>
      </div>
    </div>
  </app-layout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import ProjectDelete from '@/Pages/Project/Partials/Delete.vue'

const props = defineProps(['project', 'studies'])

const items = [
  { label: 'Dashboard', route: route('dashboard') },
  {
    label: props.project?.name,
    route: route('project', [props.project?.id]),
  },
  { label: 'Settings', route: '#' },
]
</script>
