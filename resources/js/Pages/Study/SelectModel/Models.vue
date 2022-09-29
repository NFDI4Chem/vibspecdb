<template>
    <div>
        <study-content :project="project" :study="study" current="Select Model">
            <template #study-section>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="flex justify-start p-2 px-5 flex-row">
                        <div class="font-bold">Select Model to process:</div>
                        <div class="font-lg px-5 font-bold text-teal-800">
                            {{ active?.name }}
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
                        <div v-if="models" class="h-full">
                            <div
                                class="flex-1 min-h-fit border-t border-gray-200 lg:flex h-full"
                            >
                                <section
                                    class="p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                                >
                                    <div v-if="models" class="flex-1 flex-row">
                                        <ModelItems
                                            :items="models"
                                            :active="active"
                                            @onSelect="onSelect"
                                        />
                                        <ModelInfo
                                            v-if="active.id > 0"
                                            :model="active"
                                            class="grow mx-auto max-w-2xl py-2 px-4 sm:py-4 sm:px-6 lg:max-w-7xl lg:px-8"
                                        />
                                    </div>
                                    <div v-else>
                                        <div>No models present</div>
                                    </div>
                                </section>
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
import ModelItems from "@/Pages/Study/SelectModel/ModelItems.vue";
import ModelInfo from "@/Pages/Study/SelectModel/ModelInfo.vue";
import Footer from "@/Pages/Study/Helpers/Footer.vue";

import { selectedModel, StudySubmitSteps, currentStudyStep } from "@/VueComposable/store";

import { ref, computed, onMounted, reactive } from "vue";
const props = defineProps(["study", "project", "models"]);

const onSelect = (model) => {
    selectedModel.value = model;
};

currentStudyStep.value = 2;
const steps = computed(() => StudySubmitSteps(props?.study));

const active = computed(() => selectedModel.value);

</script>

<style lang="scss" scoped></style>
