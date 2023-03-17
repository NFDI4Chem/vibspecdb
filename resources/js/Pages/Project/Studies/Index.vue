<template>
  <div>
    <div class="flex flex-col gap-5">
      <div class="flex justify-between items-center">
        <div class="flex flex-col gap-2">
          <h2 class="text-lg">Studies</h2>
          <div class="mt-2 text-sm text-gray-700">
            <div class="max-w-2xl">
              Each project may contain as many studies as you need.
            </div>
          </div>
        </div>
        <div class="flex-shrink-0 ml-4" v-if="studies.length">
          <button
            type="button"
            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            @click="openStudyCreateDialog()"
          >
            New Study
          </button>
        </div>
      </div>

      <span v-if="studies.length">
        <div class="mt4">
          <div class="px-6 py-4 bg-white shadow-md rounded-lg">
            <div class="flex items-center">
              <w-icon class="pr-1 pb-1 text-gray-500" lg
                >mdi mdi-shape-square-plus</w-icon
              >
              <div
                class="ml3 font-semibold text-sm text-gray-600 uppercase tracking-wider"
              >
                Create Your First Study
              </div>
            </div>
            <div class="mt3 max-w-2xl text-sm text-gray-700">
              A project can contain as many studies as you wish and each study
              receives its own URL. Within each study you may upload files,
              select Model to process and submit your jobs.
            </div>
            <w-flex class="mt2">
              <w-button
                class="ma1 grow"
                bg-color="primary"
                xl
                @click="openStudyCreateDialog()"
              >
                <div class="text-sm font-medium uppercase my3">
                  Create a new study
                </div>
              </w-button>
            </w-flex>
          </div>
        </div>
      </span>
      <span v-else>
        <StudyItems :items="studies" @onSelect="onSelect" />
      </span>
      <study-create ref="studyCreateElement" :project="project" />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import StudyCreate from '@/Pages/Study/Partials/Create.vue'
import StudyItems from '@/Pages/Project/Studies/StudyItems.vue'

const props = defineProps(['studies', 'project'])
const studyCreateElement = ref(null)

const openStudyCreateDialog = () => {
  studyCreateElement.value.toggleCreateStudyDialog()
}

const onSelect = study => {
  Inertia.visit(route('study', [study?.id]))
}

const tag = ref(1)
</script>
