<template>
  <div class="studies-index p-12 py-8 overflow-y-auto">
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
        <div class="flex-shrink-0 ml-4" v-if="project?.studies?.length">
          <w-button
            class="text-gray-100 p-2"
            lg
            outline
            @click="openStudyCreateDialog()"
            bg-color="cyan-dark3"
          >
            New Study
          </w-button>
        </div>
      </div>

      <span v-if="!project?.studies?.length">
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
        <StudyItems :items="project?.studies" @onSelect="onSelect" />
      </span>
    </div>
    <study-create ref="studyCreateElement" :project="project" />
  </div>
</template>

<script setup>
import { ref } from 'vue'

import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import StudyCreate from '@/Pages/Study/Partials/Create.vue'
import StudyItems from '@/Pages/Project/Studies/StudyItems.vue'

const props = defineProps(['project'])
const studyCreateElement = ref(null)

const openStudyCreateDialog = () => {
  studyCreateElement.value.toggleCreateStudyDialog()
}

const onSelect = study => {
  Inertia.visit(route('study', [study?.id]))
}

const tag = ref(1)
</script>

<style lang="scss">
.studies-index {
  width: 100%;
  height: calc(100vh - 142px);
}
</style>
