<template>

    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog
        as="div"
        class="fixed inset-0 flex z-40"
        @close="sidebarOpenChange(false)"
        :initialFocus="completeButtonRef"
      >
        <TransitionChild
          as="template"
          enter="transition-opacity ease-linear duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="transition-opacity ease-linear duration-300"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75" />
        </TransitionChild>
        <TransitionChild
          as="template"
          enter="transition ease-in-out duration-300 transform"
          enter-from="-translate-x-full"
          enter-to="translate-x-0"
          leave="transition ease-in-out duration-300 transform"
          leave-from="translate-x-0"
          leave-to="-translate-x-full"
        >
          <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white">
            <TransitionChild
              as="template"
              enter="ease-in-out duration-300"
              enter-from="opacity-0"
              enter-to="opacity-100"
              leave="ease-in-out duration-300"
              leave-from="opacity-100"
              leave-to="opacity-0"
            >
              <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button
                  type="button"
                  class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                  @click="sidebarOpenChange(false)"
                >
                  <span class="sr-only">Close sidebar</span>
                  <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                </button>
              </div>
            </TransitionChild>
          <div class="flex items-center flex-shrink-0 px-4 justify-start">
            <Link :href="route('dashboard')">
              <jet-application-logo class="block h-10 w-auto" />
            </Link>
          </div>
            <div class="mt-5 flex-1 h-0 overflow-y-auto">
              <nav class="flex-1 px-2 bg-white space-y-1">
                <div class="relative mb-4">
                  <jet-dropdown
                    v-if="$page.props.jetstream.hasTeamFeatures"
                    align="center"
                    width="60"
                  >
                    <template #trigger>
                      <span class="min-w-100 rounded-md">
                        <button
                          type="button"
                          class="text-base flex min-w-full  items-center px-3 py-2 border font-light leading-4 font-large rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition"
                        >
                          {{ $page.props.user.current_team.name }}
                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>
                    <template #content>
                      <div class="w-60">
                        <template v-if="$page.props.jetstream.hasTeamFeatures">
                          <template
                            v-for="team in $page.props.user.all_teams"
                            :key="team.id"
                          >
                            <form @submit.prevent="switchToTeam(team)">
                              <jet-dropdown-link as="button">
                                <div class="flex items-center">
                                  <svg
                                    v-if="team.id == $page.props.user.current_team_id"
                                    class="mr-2 h-5 w-5 text-green-400"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                  >
                                    <path
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                  </svg>
                                  <svg
                                    v-else
                                    class="mr-2 h-5 w-5 text-gray-400"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                  >
                                    <path
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                  </svg>
                                  <div>{{ team.name }}</div>
                                </div>
                              </jet-dropdown-link>
                            </form>
                          </template>
                          <div class="border-t border-gray-100"></div>
                          <jet-dropdown-link
                            v-if="$page.props.jetstream.canCreateTeams"
                            :href="route('teams.create')"
                          >
                            Create New Team
                          </jet-dropdown-link>
                        </template>
                      </div>
                    </template>
                  </jet-dropdown>
                </div>
                <Link
                  ref="completeButtonRef"
                  :href="route('dashboard')"
                  class="my-2 text-gray-800 group flex items-center px-2 py-3 text-sm font-medium rounded-md"
                >
                  <HomeIcon class="mr-2 h-6 w-6" aria-hidden="true" />
                  <div class="text-lg mt-1">Dashboard</div> 
                </Link>
              </nav>
            </div>
          </div>
        </TransitionChild>
        <div class="flex-shrink-0 w-14" aria-hidden="true">
          <!-- Dummy element to force sidebar to shrink to fit close icon -->
        </div>
      </Dialog>
    </TransitionRoot>
</template>

<script setup>

import { ref, computed } from "vue";

import {
  Dialog,
  DialogOverlay,
  Menu,
  MenuButton,
  MenuItem,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";

import JetDropdown from "@/Jetstream/Dropdown.vue";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";
import JetApplicationLogo from "@/Jetstream/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";

import {
  XMarkIcon,
  HomeIcon
} from "@heroicons/vue/24/outline";


import { sidebarOpen } from "@/VueComposable/store";

const completeButtonRef = ref(null)

const sidebarOpenChange = (status) => {
  sidebarOpen.value = status
}

</script>

<style lang="scss" scoped>

</style>