<template>
  <div class="flex-col min-h-screen w-16 hidden sm:flex">
    <div
      class="flex flex-col flex-grow border-r border-gray-200 bg-white overflow-y-auto text-gray-500"
    >
      <button
        v-if="!sidebarOpen"
        type="button"
        class="p-4 pb-4 border-b border-gray-200 focus:outline-none h-[64px] flex justify-center align-middle"
        @click="sidebarOpenChange(!sidebarOpen)"
      >
        <span class="sr-only">Open sidebar</span>
        <Bars3Icon class="h-7 w-7" aria-hidden="true" />
      </button>

      <div class="flex items-center flex-shrink-0 px-4" v-if="false">
        <Link :href="route('dashboard')">
          <jet-application-logo class="block h-10 w-auto" />
        </Link>
      </div>

      <div class="mt-0 flex-grow flex flex-col">
        <nav class="flex-1 px-1 bg-white space-y-3 py-3">
          <Link
            v-for="item in leftMenu"
            :key="item.name"
            :href="item.href"
            class="group flex items-center px-2 pt-1 text-sm font-medium flex-col rounded-none"
            :class="{ ' border-sky-700 border-l-2 bg-gray-100': item.active }"
          >
            <w-icon class="mr-1" xl>{{ item.icon }}</w-icon>
            <div class="my0.5 text-xs">{{ item.shortname }}</div>
          </Link>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Bars3Icon, HomeIcon, BriefcaseIcon } from '@heroicons/vue/24/outline'
import { MagnifyingGlassIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'

import { leftMenu } from '@/VueComposable/store'

import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue'
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps(['sidebarOpen'])
const emit = defineEmits(['sidebarOpenChange'])

const sidebarOpenChange = state => {
  emit('sidebarOpenChange', state)
}
</script>

<style lang="scss" scoped></style>
