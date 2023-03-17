<template>
  <app-layout :title="project?.name">
    <template #header>
      <div class="w-full flex flex-col gap-2">
        <div class="flex flex-row justify-between w-full">
          <div
            class="flex pr-20 items-center text-sm text-gray-700 uppercase font-bold tracking-widest"
          >
            {{ project?.name }}
          </div>
          <div class="group text-sm text-gray-500 ml-0 sm:ml-auto">
            <Link
              :href="route('project.settings', project?.id)"
              class="flex flex-row items-center flex-nowrap"
            >
              <div class="flex flex-row items-center gap-2">
                <Cog6ToothIcon
                  class="h-5 w-5 pb-0.5 text-gray-400 group-hover:text-teal-500"
                  aria-hidden="true"
                />
                <div class="group-hover:text-teal-500">
                  Project&nbsp;Settings
                </div>
              </div>
            </Link>
          </div>
        </div>
        <div
          class="cursor-pointer flex w-full justify-start items-center text-gray-500"
        >
          <div class="flex flex-row gap-5">
            <span
              v-if="project?.is_public"
              class="items-center inline-flex gap-0.5"
            >
              <GlobeAltIcon class="h-5 w-5" aria-hidden="true" />
              <span class="pt-0.5">Public</span>
            </span>
            <span v-else class="items-center inline-flex gap-0.5">
              <LockClosedIcon class="h-5 w-5 pb-0.5" aria-hidden="true" />
              <span class="pt-0.5">Private</span>
            </span>

            <a class="cursor-pointer items-center pt-1" @click="toggleDetails">
              <div class="flex flex-row gap-0.5">
                <IdentificationIcon class="h-5 w-5 pt-0.5" aria-hidden="true" />
                <span class="mb-0.5">View details</span>
              </div>
            </a>
          </div>
          <project-details ref="projectDetailsElement" :project="project" />
        </div>
      </div>
    </template>
    <div class="py-8 px-12">
      <div>
        <studies :project="project" :studies="studies" />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import Studies from '@/Pages/Project/Studies/Index.vue'
import ProjectDetails from './Partials/Details.vue'

import {
  Cog6ToothIcon,
  IdentificationIcon,
  GlobeAltIcon,
} from '@heroicons/vue/24/outline'
import { LockClosedIcon } from '@heroicons/vue/24/solid'

import { ref } from 'vue'

export default {
  components: {
    Link,
    AppLayout,
    Studies,
    ProjectDetails,
    Cog6ToothIcon,
    IdentificationIcon,
    LockClosedIcon,
    GlobeAltIcon,
  },
  props: ['project', 'studies'],
  setup() {
    const projectDetailsElement = ref(null)
    return {
      projectDetailsElement,
    }
  },
  data() {
    return {}
  },
  methods: {
    toggleDetails() {
      this.projectDetailsElement.toggleDetails()
    },
  },
}
</script>
