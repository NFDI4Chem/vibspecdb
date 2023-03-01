<template>
  <div
    class="fixed inset-0 z-50 flex flex-col items-end justify-center px-4 py-6 pointer-events-none sm:p-6 sm:items-start sm:justify-end"
  >
    <div
      v-for="notification in notifications"
      :key="notification?.id"
      class="w-full my-1"
    >
      <transition
        appear
        enter-active-class="transform ease-out duration-300 transition"
        enter-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="notification?.title"
          class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
          @mouseleave="leave_message(notification?.id)"
        >
          <div class="p-4 py-2">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <CheckCircleIcon
                  v-if="notification?.type === 'Success'"
                  class="text-green-500 h-7 w-7"
                />
                <ExclamationCircleIcon
                  v-if="notification?.type === 'Error'"
                  class="text-red-500 h-7 w-7"
                />
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <template v-if="notification?.title && notification?.message">
                  <p class="text-sm font-medium text-gray-900">
                    {{ notification?.title }}
                  </p>
                  <p class="mt-1 text-sm text-gray-500">
                    {{ notification?.message }}
                  </p>
                </template>
                <template v-else>
                  <p class="text-sm font-medium text-gray-900">
                    {{ notification?.message || notification?.title }}
                  </p>
                </template>
              </div>
              <div class="ml-4 flex-shrink-0 flex">
                <button
                  class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                  @click="leave_message(notification?.id)"
                >
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="h-5 w-5" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { v4 as uuidv4 } from 'uuid'

import {
  ExclamationCircleIcon,
  CheckCircleIcon,
  XMarkIcon,
} from '@heroicons/vue/24/solid'

const props = defineProps(['notifications'])
const emit = defineEmits(['onDelete'])

// const notifications = [
//     {
//         title: 'Error',
//         type: 'Error',
//         message: 'asdfasdfasdf afdsasdf asdfasdfasdf asdfasdfasdf asdfasdfasdf',
//         id: 'xxs1'
//     }
// ]

const leave_message = id => {
  emit('onDelete', id)
}
</script>
