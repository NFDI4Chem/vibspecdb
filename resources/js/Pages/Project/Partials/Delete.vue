<template>
  <div class="flex flex-col sm:flex-row justify-between gap-4 project-delete">
    <div class="flex flex-col">
      <div class="text-bold">Delete Project</div>
      <div>
        <div class="mt2 text-sm">Permanently delete your project.</div>
      </div>
    </div>

    <div class="bg-white p-4 sm:w-full md:w-2/3">
      <div class="flex flex-col gap-2">
        <div class="text-sm text-gray-600">
          Once your project is deleted, all of its resources and data will be
          permanently deleted. Before deleting your project, please download any
          data or information that you wish to retain.
        </div>

        <delete-btn :pid="project?.id">
          <div>Delete Project</div>
        </delete-btn>
      </div>
    </div>
  </div>
</template>

<script setup>
import DeleteBtn from '@/Pages/Project/Partials/DeleteBtn.vue'

const props = defineProps(['project'])
</script>
