<template>
    <app-layout title="Dashboard">
        <template #header>
            <div>
                <div
                    class="flex items-center text-sm text-gray-700 uppercase font-bold tracking-widest"
                >
                    <div v-if="team?.personal_team">Your</div>
                    <div v-else>
                        {{ user?.current_team?.name }}
                    </div>
                    &nbsp;Dashboard
                </div>
                <div
                    v-if="team?.users?.length > 1"
                    class="flex mt-3 flex-row-reverse justify-end"
                >
                    <img
                        v-for="user in team?.users"
                        :key="user?.id"
                        class="w-8 h-8 -mr-3 rounded-full border-3 border-dark"
                        :src="user?.profile_photo_url"
                        :alt="user?.name"
                    />
                </div>
            </div>
            <div v-if="!team?.personal_team">
                <a
                    :href="'/teams/' + user?.current_team?.id"
                    class="text-sm text-gray-800 font-bold"
                >
                    Team Settingss
                </a>
            </div>
        </template>
        <div class="px-4 sm:px-6 py-8 mx-auto max-w-6xl">
            <team-projects :projects="projects" />
        </div>
    </app-layout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import TeamProjects from '@/Pages/Project/Index.vue'
import { sidebarOpen, updateLeftMenu } from "@/VueComposable/store";

const props = defineProps(["user", "team", "projects"]);

sidebarOpen.value = false;

updateLeftMenu('Dashboard');

</script>
