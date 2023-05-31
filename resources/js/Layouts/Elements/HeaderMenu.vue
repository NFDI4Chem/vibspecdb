<template>
  <div class="flex flex-col flex-1">
    <div
      class="sticky top-0 left-0 z-10 flex-shrink-0 h-16 bg-white border-b flex"
    >
      <button
        type="button"
        class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none block sm:hidden"
        @click="sidebarOpenChange(!sidebarOpen)"
      >
        <span class="sr-only">Open sidebar</span>
        <Bars3Icon class="h-6 w-6" aria-hidden="true" />
      </button>
      <div class="flex-1 px4 flex justify-between">
        <div class="flex-1 flex">
          <form class="w-full flex md:ml-0" action="#" method="GET">
            <label for="search-field" class="sr-only">Search</label>
            <div
              class="relative w-full text-gray-400 focus-within:text-gray-600"
            >
              <div
                class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
              >
                <MagnifyingGlassIcon class="h-5 w-5" aria-hidden="true" />
              </div>
              <input
                id="search-field"
                class="block w-full h-full pl8 pr3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                placeholder="Search"
                type="search"
                name="search"
              />
            </div>
          </form>
        </div>

        <div class="ml4 flex items-center">
          <div>
            <div
              class="hover:cursor-pointer"
              @click="openNotificationDrawer = true"
            >
              <w-icon color="light-blue-dark2" lg v-if="nalerts === 0"
                >mdi mdi-bell-outline</w-icon
              >
              <w-badge v-else bg-color="error" class="mr5 mt1">
                <template #badge>{{ nalerts }}</template>
                <w-icon color="light-blue-dark2" lg
                  >mdi mdi-bell-ring-outline</w-icon
                >
              </w-badge>
            </div>
            <w-drawer
              v-model="openNotificationDrawer"
              right="true"
              class="z-10"
              drawer-class="overflow-y-auto overflow-x-hidden py12"
              bg-color="blue-grey-light7"
            >
              <div class="absolute top-1 left-2 title2">Notifications:</div>
              <w-button
                @click="openNotificationDrawer = false"
                sm
                outline
                round
                absolute
                icon="wi-cross"
              >
              </w-button>
              <div v-if="notificationItems?.length > 0" class="w-full">
                <w-timeline
                  class="w-full px5"
                  :items="notificationItems"
                  color="transparent"
                >
                  <template #item="{ item }">
                    <div
                      class="flex flex-row justify-between items-center border-t"
                    >
                      <div class="w-timeline-item__title" :class="item.color">
                        {{ item.title }}
                      </div>
                      <div>
                        <div class="text-xs">
                          <time>{{
                            formatDateTimeShort(item.created_at)
                          }}</time>
                        </div>
                      </div>
                    </div>
                    <div class="w-timeline-item__content">
                      {{ item.content }}
                    </div>
                    <div class="mt1 flex items-center justify-between">
                      <div
                        class="text-gray-400 text-xs max-w-[200px] text-ellipsis overflow-x-hidden whitespace-nowrap"
                      >
                        {{ `Study: ${item?.study?.name}` }}
                      </div>
                      <w-button
                        class="flex items-center text-gray-50"
                        bg-color="blue-grey"
                        @click=""
                        xs
                        :route="
                          route('study', { study: item?.study?.id, tab: 1 })
                        "
                        :disabled="!item?.study?.id"
                      >
                        Visit
                        <w-icon class="ml2">mdi mdi-arrow-right</w-icon>
                      </w-button>
                    </div>
                  </template>
                </w-timeline>
                <w-button
                  class="w-full my4 mt6"
                  @click="clear_notifications"
                  xl
                >
                  Clear All
                </w-button>
              </div>
              <div v-else class="w-full">
                <w-alert info lg class="rounded-none" border-top
                  >Notification list is empty.</w-alert
                >
              </div>
            </w-drawer>
          </div>

          <a :href="docs_page" target="_blank">
            <w-icon class="w-12" xl color="grey-light1">
              mdi mdi-book-open-page-variant-outline
            </w-icon>
          </a>

          <Menu as="div" class="ml-3 relative">
            <div>
              <MenuButton
                v-if="!$page.props.jetstream.managesProfilePhotos"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
              >
                <img
                  class="h-8 w-8 rounded-full object-cover"
                  :src="profile_photo"
                  :alt="$page.props.user.first_name"
                />
              </MenuButton>
              <span v-else class="inline-flex rounded-md">
                <MenuButton
                  type="button"
                  class="inline-flex items-center px-5 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                >
                  <img
                    class="h-8 w-8 rounded-full object-cover mr3"
                    :src="profile_photo"
                    :alt="$page.props.user.first_name"
                  />
                  <span class="md:block hidden">{{
                    $page.props.user.first_name
                  }}</span>
                  <ChevronDownIcon class="ml2 mr2 h-3 w-3" />
                </MenuButton>
              </span>
            </div>
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <MenuItems
                class="z-100 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
              >
                <span v-if="hasAnyPermission(['manage platform'])">
                  <div class="block px-4 py-2 text-xs text-gray-400">Admin</div>
                  <jet-dropdown-link :href="route('console')">
                    Console
                  </jet-dropdown-link>
                </span>

                <div class="block px-4 py-2 text-xs text-gray-400">
                  Manage Account
                </div>

                <jet-dropdown-link :href="route('profile.show')">
                  Profile
                </jet-dropdown-link>

                <jet-dropdown-link
                  v-if="$page.props.jetstream.hasApiFeatures"
                  :href="route('api-tokens.index')"
                >
                  API Tokens
                </jet-dropdown-link>

                <div class="border-t border-gray-100"></div>

                <form @submit.prevent="logout">
                  <jet-dropdown-link as="button"> Log Out </jet-dropdown-link>
                </form>
              </MenuItems>
            </transition>
          </Menu>
        </div>
      </div>
    </div>

    <!-- h-full flex flex-col relative overflow-y-auto focus:outline-none -->
    <main
      class="h-full flex flex-col relative overflow-y-hidden focus:outline-none"
    >
      <div class="bg-white border-b">
        <div class="px-5 sm:px-6 sm:pr-10">
          <div class="flex justify-between py3">
            <slot name="header"></slot>
          </div>
        </div>
      </div>
      <slot></slot>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage } from '@inertiajs/inertia-vue3'
import JetDropdownLink from '@/Jetstream/DropdownLink.vue'

import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'

import {
  Bars3Icon,
  BellIcon,
  BellAlertIcon,
  CheckCircleIcon,
  ExclamationCircleIcon,
  BriefcaseIcon,
  ArrowRightIcon,
} from '@heroicons/vue/24/outline'
import { MagnifyingGlassIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'

const props = defineProps(['sidebarOpen', 'alertItems'])
const emit = defineEmits(['sidebarOpenChange', 'logout', 'clearJobAlerts'])

const openNotificationDrawer = ref(false)

const getStatusAttributes = alert => {
  let title = 'Undefined'
  let icon = 'wi-warning-circle'
  let color = 'amber'

  switch (alert?.status) {
    case 'done':
      title = 'Success'
      icon = 'wi-check-circle'
      color = 'success'
      break
    case 'succeeded':
      title = 'Success'
      icon = 'wi-check-circle'
      color = 'success'
      break
    case 'failed':
      title = 'Failed'
      icon = 'wi-cross-circle'
      color = 'error'
      break
    case 'error':
      title = 'Error'
      icon = 'wi-cross-circle'
      color = 'error'
      break
    case 'running':
      title = 'Running'
      icon = 'wi-info-circle'
      color = 'info'
      break
    case 'info':
      title = 'Info'
      icon = 'wi-info-circle'
      color = 'info'
      break
    default:
      break
  }
  return { title, icon, color }
}

const notificationItems = computed(() => {
  return (
    props?.alertItems
      // ?.slice(0, 0)
      ?.map(alert => {
        const alert_status = getStatusAttributes(alert)
        return {
          study: alert?.study,
          content: alert?.text,
          created_at: alert?.created_at,
          ...alert_status,
        }
      })
  )
})

const sidebarOpenChange = state => {
  emit('sidebarOpenChange', state)
}

const profile_photo = computed(() => {
  return usePage().props.value?.user?.photo_url
    ? usePage().props.value?.user?.photo_url
    : usePage().props.value?.user?.profile_photo_url
})

const nalerts = computed(() => {
  return props.alertItems.length
})
const show_alerts = ref(false)

const clear_notifications = () => {
  show_alerts.value = false
  emit('clearJobAlerts')
}

const alerts = computed(() => {
  return props.alertItems
})

const docs_page = computed(() => {
  return import.meta.env.VITE_DOCS_PAGE
})

const logout = () => {
  emit('logout')
}
</script>

<style lang="scss" scoped>
.notification_number {
  position: relative;
  font-size: 12px;
  font-weight: bold;
  margin-bottom: 15px;
  margin-left: 0px;
}

.notifications-list {
  position: absolute;
  top: 63px;
  right: 38px;
  width: 320px;
  max-height: 250px;
  background: white;
  border: 1px solid lightgray;
  // border-top: none;
  color: gray;
  overflow-y: auto;
}
</style>
