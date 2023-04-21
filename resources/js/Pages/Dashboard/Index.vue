<template>
  <app-layout title="Dashboard" :projects="projects">
    <template #header>
      <div class="min-h-[54px]">
        <div>
          <div
            class="flex items-center text-md text-gray-700 tracking-widest primary"
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
      </div>
    </template>
    <div>
      <team-projects :projects="projects" />
    </div>
  </app-layout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

import TeamProjects from '@/Pages/Projects/Index.vue'

import { sidebarOpen, updateLeftMenu } from '@/VueComposable/store'

const props = defineProps(['user', 'team', 'projects'])

sidebarOpen.value = false

updateLeftMenu('Dashboard')
</script>

<style lang="scss" scoped>
.min-width-250 {
  min-width: 250px;
}
</style>
