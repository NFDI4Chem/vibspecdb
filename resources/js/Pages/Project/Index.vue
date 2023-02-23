<template>
    <div class="projects">

        <div class="flex flex-col gap-8">
            <div class="flex items-center justify-between">
            <div class="flex flex-col gap-2">
                <h2 class="text-lg">Projects</h2>
                <div class="mt-2 text-sm text-gray-700">
                    <div class="max-w-2xl">Each team can have many Projects.</div>
                </div>
            </div>

            <div class="bg-blue w-5/12 search-items">
                <w-input
                    tile
                    class="mb2 title5"
                    label="Search (by Name & Desc.)"
                    label-position="inside"
                    type="search"
                    :outline="false"
                    inner-icon-left="wi-search">
                </w-input>
            </div>

            <div class="flex-shrink-0 ml4" v-if="projects?.length">
                <button
                type="button"
                class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="openProjectCreateDialog()"
                >
                    New Project
                </button>
            </div>
            </div>
            <span v-if="!projects?.length">
                <div class="mt4">
                    <div class="px-6 py-4 bg-white shadow-md">
                    <div class="flex items-center">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        class="h-6 w-6"
                        >
                        <path
                            d="M3 6l9 4v12l-9-4V6zm14-3v2c0 1.1-2.24 2-5 2s-5-.9-5-2V3c0 1.1 2.24 2 5 2s5-.9 5-2z"
                            class="fill-current text-gray-400"
                        ></path>
                        <polygon
                            points="21 6 12 10 12 22 21 18"
                            class="fill-current text-gray-600"
                        ></polygon>
                        </svg>
                        <div
                        class="ml3 font-semibold text-sm text-gray-600 uppercase tracking-wider"
                        >
                        Create Your First Project
                        </div>
                    </div>
                    <div class="mt3 max-w-2xl text-sm text-gray-700">
                        This service is organized around projects.
                        A project can contain as many studies as you wish and each study receives its own URL.
                        Within each study you may upload files, select Model to process and submit your jobs. 
                    </div>
                    <w-flex class="mt2">
                        <w-button
                            class="ma1 grow"
                            bg-color="primary"
                            xl
                            @click="openProjectCreateDialog()"
                        >
                            <div class="text-sm font-medium uppercase my3">Create a new Project</div>
                        </w-button>
                    </w-flex>
                    </div>
                </div>
            </span>
            <span v-else>
                <ProjectItems :items="projects" @onSelect="onSelect" />
            </span>
        </div>
        <project-create ref="projectCreateElement"></project-create>
    </div>
</template>
<script setup>
import { Link, usePage } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

import ProjectCreate from "@/Pages/Project/Partials/Create.vue";
import ProjectItems from "@/Pages/Project/Index/ProjectItems.vue";

import { ref } from 'vue'

const props = defineProps(["projects"])
const projectCreateElement = ref(null)

const openProjectCreateDialog = () => {
    projectCreateElement.value.toggleCreateProjectDialog()
}

const onSelect = (project) => {
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

</style>


<!-- <script>
import useWave from "@/VueComposable/useWave";
export default {
  mixins: [useWave]
}
</script> -->

