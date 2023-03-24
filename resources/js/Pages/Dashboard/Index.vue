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
    <div>
      <splitpanes style="height: 100%" @resize="treeWidth = $event[0].size">
        <pane :size="treeWidth" class="bg-slate-50">
          <ProjectsTree :items="projects" class="p-2" />
        </pane>
        <pane :size="100 - treeWidth"
          ><team-projects :projects="projects" @showTree="onShowTree"
        /></pane>
      </splitpanes>
    </div>
  </app-layout>
</template>

<script setup>
import { ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'

import TeamProjects from '@/Pages/Dashboard/Projects/Index.vue'
import ProjectsTree from '@/Pages/Dashboard/Tree/ProjectsTree.vue'

import { sidebarOpen, updateLeftMenu } from '@/VueComposable/store'

const props = defineProps(['user', 'team', 'projects'])

sidebarOpen.value = false

const treeWidth = ref(0)
const showTree = ref(true)
const onShowTree = open => {
  showTree.value = open
  treeWidth.value = open ? 30 : 0
}

updateLeftMenu('Dashboard')
</script>
