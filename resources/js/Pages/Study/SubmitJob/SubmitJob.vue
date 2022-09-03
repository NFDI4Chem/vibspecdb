<template>
    <div>
        <study-content :project="project" :study="study" current="Submit Job">
            <template #study-section>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
                        <div class="h-full">
                            <div
                                class="flex-1 min-h-fit border-t border-gray-200 flex-row h-full"
                            >
                                <div
                                    class="p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                                >
                                    <div v-if="selectedFiles?.length" class="flex-1 flex-row">
                                        <div>Selected files: </div>
                                        <FilesTable
                                            :files="selectedFiles"
                                            @onShowDetails="onShowDetails"
                                        />
                                    </div>
                                    <div v-else>
                                        <div>No files selected</div>
                                    </div>
                                </div>
                                <div
                                    class="p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                                >
                                    <div v-if="models" class="flex-1 flex-row">
                                        <!-- <ModelItems :items="models" /> -->
                                    </div>
                                    <div v-else>
                                        <div>No models selected</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Footer :steps="steps" />
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>
import StudyContent from "@/Pages/Study/Content.vue";

import Footer from "@/Pages/Study/Helpers/Footer.vue";

import { selectedFiles } from "@/VueComposable/store";
import FilesTable from "@/Pages/Study/Files/FilesTable.vue";

import { ref, computed, onMounted, reactive } from "vue";
const props = defineProps(["study", "project", "models"]);

const link_url = {
    files: `/studies/${props?.study?.id}/files`,
    models: `/studies/${props?.study?.id}/models`,
    jobs: `/studies/${props?.study?.id}/jobs`,
};

const steps = computed(() => [
    {
        name: "Step 1: select files to process",
        href: link_url.files,
        status: "",
    },
    {
        name: "Step 2: select model to process del to process",
        href: link_url.models,
        status: "",
    },
    {
        name: "Step 3: view job details & submit",
        href: link_url.jobs,
        status: "current",
    },
]);

const onShowDetails = (rowId) => {
    selectedFiles.value = selectedFiles.value.map((item) => {
        return rowId === item.id
            ? {
                  ...item,
                  detailsOpen: !item?.detailsOpen,
              }
            : item;
    });
};

</script>

<style lang="scss" scoped></style>
