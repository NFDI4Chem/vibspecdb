<template>
  <div
    class="max-w-sm bg-white text-center shadow-lg rounded-lg overflow-hidden my-4 hover:opacity-75 hover:cursor-pointer"
    @click="onSelect(item)"
  >
    <img
      class="w-32 h-32 object-center m-auto my-6"
      src="/imgs/assay/assay.svg"
      alt="avatar"
    />
    <div class="flex items-center px-6 py-1 text-white bg-gray-500">
      <BriefcaseIcon class="w-5 h-5" />
      <h1 class="mx-3 text-white font-semibold text-md">
        #0 Studies / #0 Active Jobs
      </h1>
    </div>
    <div class="py-4 px-5 text-gray-500">
      <h1 class="text-2xl font-semibold text-gray-800 text-left">
        {{ item.name }}
      </h1>
      <p
        class="py-1 text-lg text-gray-700 special-ellipsis"
        v-if="item?.description?.length"
      >
        {{ item.description }}
      </p>
      <div class="flex items-center mt-4">
        <span v-if="item.is_public" class="inline-flex items-center">
          <GlobeAltIcon class="h-5 w-5" aria-hidden="true" />
          <h1 class="px-2 text-sm pt-1">Public</h1>
        </span>
        <span v-else class="inline-flex items-center">
          <LockClosedIcon class="h-5 w-5" aria-hidden="true" />
          <h1 class="ml-2 pt-1">Private</h1>
        </span>
      </div>
      <div class="flex items-center mt-1">
        <CalendarIcon class="h-5 w-5" aria-hidden="true" />
        <h1 class="px-2 text-sm pt-1">
          {{ formatDate(item?.created_at) }}
        </h1>
      </div>
      <div class="flex items-center mt-1">
        <UserIcon class="h-5 w-5" aria-hidden="true" />
        <h1 class="px-2 text-sm pt-1">Owner / Shared</h1>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  BriefcaseIcon,
  GlobeAltIcon,
  CalendarIcon,
} from '@heroicons/vue/24/outline'
import { LockClosedIcon, UserIcon } from '@heroicons/vue/24/solid'

const props = defineProps(['item'])
const emit = defineEmits(['onSelect'])

const onSelect = model => {
  emit('onSelect', model)
}
</script>

<style lang="scss" scoped>
.special-ellipsis {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  text-align: left;
}
</style>
