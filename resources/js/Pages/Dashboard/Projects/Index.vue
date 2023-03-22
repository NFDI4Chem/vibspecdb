<template>
  <div>
    <w-drawer
      v-model="openDrawer"
      push-content
      :overlay-opacity="0.15"
      left
      width="250px"
      :no-overlay="true"
    >
      <template #pushable>
        <div
          class="projects-index p-12 py-8 overflow-y-auto"
          :class="{ 'projects-expanded': openDrawer }"
        >
          <w-button
            lg
            icon="mdi mdi-file-tree"
            absolute
            bg-color="cyan-dark3"
            class="top-0 left-2 text-gray-100"
            @click="openDrawer = !openDrawer"
          />
          <div class="flex flex-col gap-8">
            <div class="flex items-center justify-between">
              <div class="flex flex-col gap-2">
                <h2 class="text-lg">Projects</h2>
                <div class="mt-2 text-sm text-gray-700">
                  <div class="max-w-2xl">Each team can have many Projects.</div>
                </div>
              </div>

              <div class="bg-blue w-5/12 search-items" v-if="false">
                <w-input
                  tile
                  class="mb2 title5"
                  label="Search (by Name & Desc.)"
                  label-position="inside"
                  type="search"
                  :outline="false"
                  inner-icon-left="wi-search"
                >
                </w-input>
              </div>

              <div class="flex-shrink-0 ml4" v-if="projects?.length">
                <w-button
                  class="text-gray-100 p-2"
                  lg
                  outline
                  @click="openProjectCreateDialog()"
                  bg-color="cyan-dark3"
                >
                  New Project
                </w-button>
              </div>
            </div>
            <span v-if="!projects?.length">
              <div class="mt4">
                <div class="px-6 py-4 bg-white shadow-md">
                  <div class="flex items-center">
                    <w-icon class="pr-1 pb-1 text-gray-500" lg
                      >mdi mdi-shape-square-plus</w-icon
                    >
                    <div
                      class="ml3 font-semibold text-sm text-gray-600 uppercase tracking-wider"
                    >
                      Create Your First Project
                    </div>
                  </div>
                  <div class="mt3 max-w-2xl text-sm text-gray-700">
                    This service is organized around projects. A project can
                    contain as many studies as you wish and each study receives
                    its own URL. Within each study you may upload files, select
                    Model to process and submit your jobs.
                  </div>
                  <w-flex class="mt2">
                    <w-button
                      class="ma1 grow"
                      bg-color="primary"
                      xl
                      @click="openProjectCreateDialog"
                    >
                      <div class="text-sm font-medium uppercase my3">
                        Create a new Project
                      </div>
                    </w-button>
                  </w-flex>
                </div>
              </div>
            </span>
            <span v-else>
              <ProjectItems :items="projects" @onSelect="onSelect" />
            </span>
          </div>
          <project-create ref="projectCreateElement" />
        </div>
      </template>

      <div class="ma2">Drawer content</div>
    </w-drawer>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

import ProjectCreate from '@/Pages/Project/Partials/Create.vue'
import ProjectItems from '@/Pages/Dashboard/Projects/ProjectItems.vue'

const props = defineProps(['projects'])
const projectCreateElement = ref()
const openDrawer = ref(false)

const openProjectCreateDialog = () => {
  projectCreateElement.value.toggleCreateProjectDialog()
}

const onSelect = project => {
  Inertia.visit(route('project', [project?.id]))
}
</script>

<style lang="scss">
.search-items {
  .w-input {
    .w-input__icon {
      margin: 8px 10px 10px 0px;
    }
    .w-input__input {
      height: 2rem;
    }
  }
}

.projects-index {
  width: 100%;
  height: calc(100vh - 125px);
}

.projects-expanded {
  width: calc(100% - 250px);
}
</style>
