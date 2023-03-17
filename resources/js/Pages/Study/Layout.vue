<template>
  <app-layout :title="study?.name">
    <template #header>
      <div class="lg:flex lg:items-center lg:justify-between w-full">
        <div class="flex-1 min-w-0 flex-col space-y-2">
          <nav class="flex" aria-label="Breadcrumb">
            <ol role="list" class="flex items-center space-x-4">
              <li>
                <div class="flex">
                  <Link
                    :href="route('dashboard')"
                    class="text-sm font-medium text-gray-500 hover:text-gray-700"
                    >Dashboard</Link
                  >
                </div>
              </li>
              <li>
                <div class="flex items-center">
                  <div class="flex flex-row gap-3">
                    <ChevronRightIcon
                      class="flex-shrink-0 h-5 w-5 text-gray-400"
                      aria-hidden="true"
                    />
                    <Link
                      :href="route('project', [project?.id])"
                      class="text-sm font-medium text-gray-500 hover:text-gray-700"
                      >{{ project?.name }}</Link
                    >
                  </div>
                </div>
              </li>
            </ol>
          </nav>
          <div class="flex flex-row justify-between items-center">
            <h2
              class="text-2xl font-bold break-words leading-7 text-gray-900 sm:text-3xl"
            >
              {{ study?.name }}
            </h2>
            <div class="group text-sm text-gray-500 ml-0 sm:ml-auto">
              <Link
                :href="route('study.settings', study?.id)"
                class="flex flex-row items-center flex-nowrap space-x-2"
              >
                <Cog6ToothIcon
                  class="h-6 w-6 sm:h-5 sm:w-5 text-gray-800 sm:text-gray-400 group-hover:text-teal-500"
                  aria-hidden="true"
                />
                <div class="mt-0.5 group-hover:text-teal-500 hidden sm:block">
                  Study Settings
                </div>
              </Link>
            </div>
          </div>
          <div
            class="flex flex-col sm:flex-row sm:flex-wrap mt-4 sm:mt-3 gap-2 sm:gap-5 w-full"
          >
            <div class="flex items-center text-sm text-gray-500">
              <div
                v-if="study?.is_public"
                class="flex flex-row space-x-2 items-center"
              >
                <GlobeAltIcon class="h-5 w-5" aria-hidden="true" />
                <div class="pt-0.5">Public</div>
              </div>
              <span v-else class="flex-row flex space-x-1 items-center">
                <LockClosedIcon class="h-5 w-5 pb-0.5" aria-hidden="true" />
                <span class="pt-0.5">Private</span>
              </span>
            </div>
            <div class="flex flex-row text-sm text-gray-500">
              <CalendarIcon
                class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                aria-hidden="true"
              />
              <div class="mt-0.5">
                Updated on
                {{ formatDateTime(study?.updated_at) }}
              </div>
            </div>
            <div class="flex items-center text-sm text-gray-500">
              <a
                class="cursor-pointer inline-flex items-center"
                @click="toggleDetails"
              >
                <div class="flex flex-row space-x-1 items-center">
                  <IdentificationIcon
                    class="h-5 w-5 text-gray-400 group-hover:text-gray-500"
                    aria-hidden="true"
                  />
                  <div class="mt-0.5">View details</div>
                </div>
              </a>
            </div>
            <study-details ref="studyDetailsElement" :study="study" />
          </div>
        </div>
      </div>
    </template>
    <div class="px-0 sm:px-0 flex flex-1 flex-col">
      <slot name="scontent"></slot>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Link } from '@inertiajs/inertia-vue3'
import StudyDetails from './Partials/Details.vue'
import { ref } from 'vue'
import {
  BriefcaseIcon,
  CalendarIcon,
  CheckIcon,
  ChevronDownIcon,
  ChevronRightIcon,
  CurrencyDollarIcon,
  LinkIcon,
  MapPinIcon,
  PencilIcon,
  LockClosedIcon,
} from '@heroicons/vue/24/solid'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import {
  GlobeAltIcon,
  Cog6ToothIcon,
  IdentificationIcon,
} from '@heroicons/vue/24/outline'

export default {
  components: {
    Link,
    AppLayout,
    StudyDetails,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    BriefcaseIcon,
    CalendarIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    CurrencyDollarIcon,
    LinkIcon,
    MapPinIcon,
    PencilIcon,
    GlobeAltIcon,
    LockClosedIcon,
    Cog6ToothIcon,
    IdentificationIcon,
  },
  props: ['study', 'project'],
  setup() {
    const studyDetailsElement = ref(null)
    return {
      studyDetailsElement,
    }
  },
  data() {
    return {}
  },
  methods: {
    toggleDetails() {
      this.studyDetailsElement.toggleDetails()
    },
  },
}
</script>
