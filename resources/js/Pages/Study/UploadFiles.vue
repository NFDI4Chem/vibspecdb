<template>
    <div>
        <study-content :project="project" :study="study" current="Upload Files">
            <template #study-section>
                <UploadFormUppy
                    pid="3"
                    id="JobsUppyInstance"
                    v-if="uppyShow"
                    :maxFileSize="maxFileSize"
                    @uploaded="onUploaded"
                    @onBeforeUpload="onBeforeUploadUppy"
                    ref="UploadFormUppyRef"
                    dashboardWidth="100%"
                    dashboardHeight="700"
                    @handleProgress="handleProgress"
                    @uploadProgress="uploadProgress"
                    v-model:UppyState="UppyState"
                    :stopUpload="true"
                />
                <div
                    class="uploading-progress flex flex-row justify-center gap-20 items-center py-10 px-12"
                >
                    <div>
                        <radial-progress-bar
                            :diameter="350"
                            :innerStrokeWidth="15"
                            :strokeWidth="15"
                            :completed-steps="progress"
                            innerStrokeColor="#ccd1d3"
                            startColor="#059faf"
                            stopColor="#0b659d"
                            :total-steps="100"
                        >
                            <img
                                src="/imgs/uploading/UploadingIcon.png"
                                alt=""
                                class="w-3/4 h-auto"
                            />
                        </radial-progress-bar>
                    </div>
                    <div
                        class="uploading-description flex flex-col items-center gap-5"
                    >
                        <div class="font-bold text-5xl text-gray-700">Uploading files</div>
                        <div class="text-3xl">{{ progress }}%</div>
                        <div class="text-center text-2xl p-5 bg-gray-200">
                            We're uploading your files to the main server. Feel
                            free to work on your tasks in the meantime.
                        </div>
                        <div
                            class="mt-5 text-sky-600 text-2xl hover:cursor-pointer"
                            @click="cancelUploading"
                        >
                            Cancel upload
                        </div>
                    </div>
                </div>
                <div class="border-t-gray-200 border-t-2 border-b-2 h-14">
                    <div
                        class="h-full flex flex-row  justify-between px-4 text-gray-400 text-lg"
                    >
                        <div class="flex flex-row gap-3 h-full items-center hover:cursor-pointer">
                            <ExternalLinkIcon
                                class="h-6 w-6 rotate-90"
                                aria-hidden="true"
                            />
                            <div class="uppercase text-grey-200 font-bold">
                                Show Modal
                            </div>
                        </div>
                        <div class="flex flex-row h-full items-center hover:cursor-pointer">
                            <InformationCircleIcon 
                                class="h-8 w-8 text-gray-500"
                                aria-hidden="true"
                            />
                        </div>
                    </div>
                </div>

                <div class="w-3/4 p-2" v-if="!uppyShow">
                    <div class="flex flex-row justify-between gap-2">
                        <Button v-if="!uppyShow" @click="uploadStart"
                            >Upload</Button
                        >
                    </div>
                </div>
            </template>
        </study-content>
    </div>
</template>

<script>
import { reactive, toRefs, ref, onMounted, computed } from "vue";
import StudyContent from "@/Pages/Study/Content.vue";
import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
// import UppyUploadForm from "@/Components/UploadForm/UppyUploadForm.vue";
import Button from "@/Jetstream/Button.vue";

import RadialProgressBar from "vue3-radial-progress";

import { ExternalLinkIcon, InformationCircleIcon } from "@heroicons/vue/outline";

import { useStore } from "vuex";

export default {
    components: {
        StudyContent,
        UploadFormUppy,
        Button,
        RadialProgressBar,
        ExternalLinkIcon,
        InformationCircleIcon
    },
    props: ["study", "project", "jobs"],
    setup() {
        const state = reactive({
            count: 0,
            listRecent: {},
            progress: 67,
        });

        const UppyState = computed({
            get() {
                return store.state.Uppy.uppy;
            },
            set(val) {
                store.dispatch("updateUppyState", val);
            },
        });

        const store = useStore();
        const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb
        const uppyShow = ref(true);
        const UploadFormUppyRef = ref();

        const onUploaded = (data) => {
            console.log("onUploaded", data);
        };

        const testAction = () => {
            axios.get("/projects/1/activity").then((response) => {
                console.log("response", response);
                state.listRecent = response?.data?.audit;
            });
        };

        const delay = (time) => {
            return new Promise((resolve) => setTimeout(resolve, time));
        };

        const onBeforeUploadUppy = async ({ files, state }) => {
            store.dispatch("updateUppyState", state);
            store.dispatch("updateShow", { files: true });
            uppyShow.value = false;
            await delay(100);
            store.dispatch("updateStartUpload", { files: true });
        };

        const handleProgress = () => {};

        const uploadProgress = () => {};

        const uploadModalStart = () => {
            store.dispatch("updateStartUpload", { files: true });
        };

        onMounted(() => {
            // UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 });
        });

        const setSize = () => {
            UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 });
        };

        const uploadStart = () => {
            uppyShow.value = true;
        };

        const updateState = () => {};

        const cancelUploading = () => {};

        return {
            ...toRefs(state),
            onUploaded,
            testAction,
            maxFileSize,
            uppyShow,
            setSize,
            UploadFormUppyRef,
            UppyState,
            uploadStart,
            onBeforeUploadUppy,
            handleProgress,
            uploadProgress,
            updateState,
            uploadModalStart,
            cancelUploading,
        };
    },
    methods: {},
};
</script>

<style lang="scss" scoped></style>
