<template>
    <div>
        <div class="flex items-baseline justify-between">
          <div>
              <h2 class="text-lg">Assays</h2>
              <div class="mt-2 text-sm text-gray-700">
              <div class="max-w-2xl">Each team can have many Assays.</div>
              </div>
          </div>
          <div class="flex-shrink-0 ml-4" v-if="assays?.length">
              <button
              type="button"
              class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              @click="openAssayCreateDialog()"
              >
                New Assay
              </button>
          </div>
        </div>
        <span v-if="!assays?.length">
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
                    Create Your First Assay
                    </div>
                </div>
                <div class="mt-3 max-w-2xl text-sm text-gray-700">
                    This service is organized around assays.
                    A assay can contain as many studies as you wish and each study receives its own URL.
                    Within each study you may upload files, select Model to process and submit your jobs. 
                </div>
                <button
                    type="button"
                    class="flex align-middle text-white justify-center text-sm font-medium px-3 py-2 uppercase w-full rounded-lg bg-sky-800 hover:bg-sky-800 focus:outline-none focus:ring-2 mt-6 focus:ring-offset-2 focus:ring-teal-500"
                    @click="openAssayCreateDialog()"
                >
                    Create a new Assay
                </button>
                </div>
            </div>
        </span>
        <span v-else>
            <AssayItems :items="assays" @onSelect="onSelect" />
        </span>
        <assay-create ref="assayCreateElement"></assay-create>
    </div>
</template>
<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from '@inertiajs/inertia';

import AssayCreate from "@/Pages/Assay/Partials/Create.vue";
import AssayItems from "@/Pages/Assay/Index/AssayItems.vue";

import { ref } from 'vue'

const props = defineProps(["assays"])
const assayCreateElement = ref(null)

const openAssayCreateDialog = () => {
    assayCreateElement.value.toggleCreateAssayDialog()
}

const onSelect = (assay) => {
  Inertia.visit(route('assay', [assay?.id])) 
}

</script>
