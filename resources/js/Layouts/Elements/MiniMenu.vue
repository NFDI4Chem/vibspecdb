<template>
    <div class="flex-col min-h-screen w-14 hidden sm:flex">
        <div
            class="flex flex-col flex-grow border-r border-gray-200 bg-white overflow-y-auto text-gray-500"
        >
            <button
                v-if="!sidebarOpen"
                type="button"
                class="p-4 pb-4 border-b border-gray-200  focus:outline-none h-[64px]"
                @click="sidebarOpenChange(!sidebarOpen)"
            >
                <span class="sr-only">Open sidebar</span>
                <Bars3Icon class="h-6 w-6" aria-hidden="true" />
            </button>

            <div class="flex items-center flex-shrink-0 px-4" v-if="false">
                <Link :href="route('dashboard')">
                    <jet-application-logo class="block h-10 w-auto" />
                </Link>
            </div>

            <div class="mt-0 flex-grow flex flex-col">
                <nav class="flex-1 px-2 bg-white space-y-1">
                    <Link
                      v-for="item in leftMenu"
                      :key="item.name"
                      :href="item.href"
                      class="my-1 group flex items-center px-2 py-3 text-sm font-medium rounded-md flex-col"
                    >
                      <component
                        :is="item.icon"
                        class="h-6 w-6 mr-1"
                        aria-hidden="true"
                      />
                      <div class="mt-1 extra-small">{{item.shortname}}</div>
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>

import {
    Bars3Icon,
    HomeIcon,
    BriefcaseIcon,
} from "@heroicons/vue/24/outline";
import { MagnifyingGlassIcon, ChevronDownIcon } from "@heroicons/vue/24/solid";

import { leftMenu } from "@/VueComposable/store";

import JetApplicationLogo from "@/Jetstream/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";

const props = defineProps(['sidebarOpen'])
const emit = defineEmits(['sidebarOpenChange'])

const sidebarOpenChange = (state) => {
  emit('sidebarOpenChange', state)
}

</script>

<style lang="scss" scoped></style>
