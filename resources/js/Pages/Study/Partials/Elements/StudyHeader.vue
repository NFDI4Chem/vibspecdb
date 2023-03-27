<template>
  <div class="w-full flex flex-col gap-3 projects-index text-sm">
    <div class="flex flex-row justify-between w-full">
      <w-breadcrumbs
        icon="mdi mdi-arrow-right"
        :items="items"
        color="light-blue-dark3"
      />
    </div>
    <div
      class="cursor-pointer flex w-full justify-start items-center text-gray-500 text-sm align-middle"
    >
      <div class="flex flex-row gap-5 w-full ml-1">
        <div
          v-if="study?.is_public"
          class="items-center inline-flex gap-0.5 align-middle"
        >
          <w-icon class="h-5 w-5">mdi mdi-earth</w-icon>
          <div>Public</div>
        </div>
        <div v-else class="flex gap-0.5 align-middle">
          <w-icon class="h-5 w-5 pb-0.5">mdi mdi-lock</w-icon>
          <div>Private</div>
        </div>

        <div class="flex flex-row" v-if="true">
          <w-icon class="h-5 w-5">mdi mdi-calendar-blank-outline</w-icon>
          <div>
            Updated on
            {{ formatDateTimeShort(study?.updated_at) }}
          </div>
        </div>
        <div class="flex flex-row gap-0.5" @click="emit('toggleDetails')">
          <w-icon class="h-5 w-5">mdi mdi-card-account-details-outline</w-icon>
          <div class="mb-0.5">View details</div>
        </div>
      </div>

      <div class="group text-sm text-gray-500 ml-0 sm:ml-auto flex">
        <Link
          :href="route('study.settings', study?.id)"
          class="flex flex-row items-center flex-nowrap"
        >
          <div class="flex flex-row items-center gap-1 align-middle">
            <w-icon
              class="h-5 w-5 pb-0.5 text-gray-400 group-hover:text-teal-500"
              >mdi mdi-cog</w-icon
            >
            <div class="group-hover:text-teal-500">Study&nbsp;Settings</div>
          </div>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'

const emit = defineEmits(['toggleDetails'])
const props = defineProps(['study', 'project'])

const items = [
  { label: 'Dashboard', route: route('dashboard') },
  {
    label: props.project?.name,
    route: route('project', [props.project?.id]),
  },
  { label: props.study?.name, route: '#' },
]
</script>
