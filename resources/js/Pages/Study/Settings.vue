<template>
  <app-layout :title="study?.name">
    <template #header>
      <w-breadcrumbs
        icon="mdi mdi-arrow-right"
        :items="items"
        color="light-blue-dark3"
      />
    </template>
    <div class="py-12 px-10">
      <div>
        <study-delete :study="study"></study-delete>
      </div>
    </div>
  </app-layout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import StudyDelete from '@/Pages/Study/Partials/Delete.vue'

const props = defineProps(['study', 'project'])

const items = [
  { label: 'Dashboard', route: route('dashboard') },
  {
    label: props.project?.name,
    route: route('project', [props.project?.id]),
  },
  { label: props.study?.name, route: route('study', [props.study?.id]) },
  { label: 'Settings', route: '#' },
]
</script>
