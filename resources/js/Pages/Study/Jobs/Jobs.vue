<template>
  <div>
    <study-content :project="project" :study="study" current="Jobs">
      <template #study-section>
        <div class="flex flex-1 flex-col justify-between">
          <div class="flex justify-start p-2 px-5 flex-row">
            <div class="font-bold">Study's Jobs Overview:</div>
          </div>
          <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
            <div v-if="study_jobs" class="h-full">
              <div
                class="flex-1 min-h-fit border-t border-gray-200 lg:flex h-full"
              >
                <section
                  class="p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                >
                  <JobsTable
                    :jobs="study_jobs"
                    @onShowDetails="onShowJobDetails"
                  />
                </section>
              </div>
            </div>
          </div>
        </div>
      </template>
    </study-content>
  </div>
</template>

<script setup>
import StudyContent from '@/Pages/Study/Content.vue'
import JobsTable from '@/Pages/Study/Jobs/JobsTable.vue'

import { jobs, onShowJobDetails } from '@/VueComposable/store'

import { ref, computed, onMounted, reactive } from 'vue'
const props = defineProps(['study', 'project', 'jobs'])

jobs.value = props?.jobs
const study_jobs = computed(() => jobs.value)
</script>

<style lang="scss" scoped></style>
