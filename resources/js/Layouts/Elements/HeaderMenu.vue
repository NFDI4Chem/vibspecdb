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
            <div class="flex-1 px-4 flex justify-between">
                <div class="flex-1 flex">
                    <form class="w-full flex md:ml-0" action="#" method="GET">
                        <label for="search-field" class="sr-only">Search</label>
                        <div
                            class="relative w-full text-gray-400 focus-within:text-gray-600"
                        >
                            <div
                                class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                            >
                                <MagnifyingGlassIcon
                                    class="h-5 w-5"
                                    aria-hidden="true"
                                />
                            </div>
                            <input
                                id="search-field"
                                class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent sm:text-sm"
                                placeholder="Search"
                                type="search"
                                name="search"
                            />
                        </div>
                    </form>
                </div>
                <div class="ml-4 flex items-center md:ml-6">
                    <a href="#" target="_blank"
                        ><svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            class="h-6 w-6"
                        >
                            <path
                                d="M12 21a2 2 0 0 1-1.41-.59l-.83-.82A2 2 0 0 0 8.34 19H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h4a5 5 0 0 1 4 2v16z"
                                class="fill-current text-gray-400"
                            ></path>
                            <path
                                d="M12 21V5a5 5 0 0 1 4-2h4a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1h-4.34a2 2 0 0 0-1.42.59l-.83.82A2 2 0 0 1 12 21z"
                                class="fill-current text-gray-600"
                            ></path></svg></a>
                    <Menu as="div" class="ml-3 relative">
                        <div>
                            <MenuButton
                                v-if="
                                    !$page.props.jetstream.managesProfilePhotos
                                "
                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                            >
                                <img
                                    class="h-8 w-8 rounded-full object-cover"
                                    :src="$page.props.user.profile_photo_url"
                                    :alt="$page.props.user.first_name"
                                />
                            </MenuButton>
                            <span v-else class="inline-flex rounded-md">
                                <MenuButton
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                                >
                                    <img
                                        class="h-8 w-8 rounded-full object-cover mr-2"
                                        :src="
                                            $page.props.user.profile_photo_url
                                        "
                                        :alt="$page.props.user.first_name"
                                    />
                                    <span class="md:block hidden">{{
                                        $page.props.user.first_name
                                    }}</span>
                                    <ChevronDownIcon
                                        class="ml-2 -mr-0.5 h-3 w-3"
                                    />
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
                                <span
                                    v-if="hasAnyPermission(['manage platform'])"
                                >
                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        Admin
                                    </div>
                                    <jet-dropdown-link :href="route('console')">
                                        Console
                                    </jet-dropdown-link>
                                </span>

                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Manage Account
                                </div>

                                <jet-dropdown-link
                                    :href="route('profile.show')"
                                >
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
                                    <jet-dropdown-link as="button">
                                        Log Out
                                    </jet-dropdown-link>
                                </form>
                            </MenuItems>
                        </transition>
                    </Menu>
                </div>
            </div>
        </div>

        <main
            class="flex flex-1 flex-col relative overflow-y-auto focus:outline-none"
        >
            <div class="bg-white border-b">
                <div class="px-5 sm:px-12">
                    <div class="flex justify-between py-5">
                        <slot name="header"></slot>
                    </div>
                </div>
            </div>
            <slot></slot>
        </main>
    </div>
</template>

<script setup>

import { Link } from "@inertiajs/inertia-vue3";
import JetDropdownLink from "@/Jetstream/DropdownLink.vue";

import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems
} from "@headlessui/vue";

import {
    Bars3Icon
} from "@heroicons/vue/24/outline";
import { MagnifyingGlassIcon, ChevronDownIcon } from "@heroicons/vue/24/solid";

const props = defineProps(['sidebarOpen'])
const emit = defineEmits(['sidebarOpenChange', 'logout'])

const sidebarOpenChange = (state) => {
  emit('sidebarOpenChange', state)
}

const logout = () => {
  emit('logout')
}

</script>

<style lang="scss" scoped></style>