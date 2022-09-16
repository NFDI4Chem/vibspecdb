<template>
    <app-layout title="Job Models">
        <template #header>
            <div class="font-bold text-base">Job Models</div>
        </template>
        <div class="flex items-baseline justify-center max-w-md"  v-if="job_models?.length">
            <div>
                <h2 class="text-lg">Job Models</h2>
                <div class="mt-2 text-sm text-gray-700">
                    <div class="max-w-2xl">
                        Each team can have many Projects.
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0 ml-4">
                <button
                    type="button"
                    class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    @click="openJobModelCreateDialog()"
                >
                    New Job Model
                </button>
            </div>
        </div>
        <span v-if="!job_models?.length">
            <div class="mt-4">
                <div class="px-6 py-4 bg-white shadow-md rounded-lg">
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
                            class="ml-3 font-semibold text-sm text-gray-600 uppercase tracking-wider"
                        >
                            Create Your First Job Model
                        </div>
                    </div>
                    <div class="mt-3 max-w-2xl text-sm text-gray-700">
                        Job Models description
                    </div>
                    <button
                        type="button"
                        class="flex align-middle text-white justify-center text-sm font-medium px-3 py-2 uppercase w-full rounded-lg bg-sky-800 hover:bg-sky-800 focus:outline-none focus:ring-2 mt-6 focus:ring-offset-2 focus:ring-teal-500"
                        @click="openJobModelCreateDialog()"
                    >
                        Create a new Job Model
                    </button>
                </div>
            </div>
        </span>
        <span v-else>
            <!-- <ProjectItems :items="projects" @onSelect="onSelect" /> -->
        </span>
        <job-model-create
            v-if="false"
            ref="jobmodelCreateElement"
        ></job-model-create>
    </app-layout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

import JobModelCreate from "@/Pages/JobModel/Partials/Create.vue";
// import ProjectItems from "@/Pages/Project/Index/ProjectItems.vue";

import { ref } from "vue";

const props = defineProps(["job_models"]);
const jobmodelCreateElement = ref(null);

const openJobModelCreateDialog = () => {
    jobmodelCreateElement.value.toggleCreateJobModelDialog();
};

// const onSelect = (project) => {
//     Inertia.visit(route("project", [project?.id]));
// };
</script>
