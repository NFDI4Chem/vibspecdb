<template>
  <app-layout :title="assay.name">
    <template #header>
      <div class="w-full">
        <div class="flex flex-row justify-between w-full">
          <div
            class="flex pr-20 items-center text-sm text-gray-700 uppercase font-bold tracking-widest"
          >
            {{ assay.name }}
          </div>
          <div class="group text-sm text-gray-500 ml-0 sm:ml-auto">
            <Link
              :href="route('assay.settings', assay.id)"
              class="flex flex-row items-center flex-nowrap"
            >
              <Cog6ToothIcon
                class="h-5 w-5 text-gray-400 group-hover:text-teal-500"
                aria-hidden="true"
              />
              <div class="ml-2 mt-0.5 group-hover:text-teal-500">
                Assay&nbsp;Settings
              </div>
            </Link>
          </div>
        </div>
        <div
          class="cursor-pointer flex w-full justify-start items-center mt-3 text-gray-500"
        >
          <span v-if="assay.is_public" class="inline-flex items-center">
            <GlobeAltIcon class="h-5 w-5" aria-hidden="true" />
            <span class="ml-2 pt-0.5">Public</span>
          </span>
          <span v-else class="inline-flex items-center">
            <LockClosedIcon class="h-4 w-4" aria-hidden="true" />
            <span class="ml-2 pt-0.5">Private</span>
          </span>
          <a
            class="cursor-pointer inline-flex items-center ml-7"
            @click="toggleDetails"
          >
            <IdentificationIcon class="h-5 w-5" aria-hidden="true" />
            <span class="ml-2 pt-0.5">View details</span></a
          >
          <assay-details ref="assayDetailsElement" :assay="assay" />
        </div>
      </div>
    </template>
    <div class="py-12 px-10">
      <div>
        <study-index :assay="assay" :studies="studies" />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import StudyIndex from '@/Pages/Study/Index.vue'
import AssayDetails from './Partials/Details.vue'

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
    StudyIndex,
    AssayDetails,
    Cog6ToothIcon,
    IdentificationIcon,
    LockClosedIcon,
    GlobeAltIcon,
  },
  props: ['assay', 'studies'],
  setup() {
    const assayDetailsElement = ref(null)
    return {
      assayDetailsElement,
    }
  },
  data() {
    return {}
  },
  methods: {
    toggleDetails() {
      this.assayDetailsElement.toggleDetails()
    },
  },
}
</script>
