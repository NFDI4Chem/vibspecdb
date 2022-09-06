<template>
    <div>
        <study-content :project="project" :study="study" current="Submit Job">
            <template #study-section>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
                                            <div class="flex justify-start p-2 px-6 flex-row">
                        <div class="font-bold">Check Job overview and submit</div>
                    </div>
                        <div class="py-3 px-6">
                            <JobPreview
                                :files="selectedFiles"
                                @onShowDetails="onShowDetails"
                                :model="selectedModel"
                            />
                            <div class="grow flex flex-1 flex-row justify-end my-3">
                                <JetButton
                                    :disabled="!selectedFiles.length || !selectedModel"
                                    class="bg-teal-600 hover:bg-teal-700 text-white font-bold rounded disabled:opacity-25"
                                >
                                    Submit Job
                                </JetButton>
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
import JetButton from "@/Jetstream/Button.vue";
import StudyContent from "@/Pages/Study/Content.vue";
import JobPreview from "@/Pages/Study/SubmitJob/JobPreview.vue";
import Footer from "@/Pages/Study/Helpers/Footer.vue";

import { selectedFiles, onShowDetails, selectedModel } from "@/VueComposable/store";
import { currentStudyStep, StudySubmitSteps } from "@/VueComposable/store";

import { computed } from "vue";
const props = defineProps(["study", "project", "models"]);

currentStudyStep.value = 3;
const steps = computed(() => StudySubmitSteps(props?.study));

</script>

<style lang="scss" scoped></style>
