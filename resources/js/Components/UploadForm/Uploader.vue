<template>
    <div class="relative">
        <UploadFormUppy
            class="sticky top-0 left-0 right-0 bottom-0"
            pid="3"
            id="JobsUppyInstance"
            v-if="!uppyUploading"
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
            dashboardLimitText="Images only, 2–3 files, up to 5 Gb (TODO: change limits here)"
            :baseFolder="baseFolder"
        />
        <div
            v-if="uppyUploading"
            :class="
                uppyUploading
                    ? 'sticky'
                    : 'absolute top-0 left-0 right-0 bottom-0'
            "
        >
            <div
                class="uploading-progress flex flex-row flex-wrap justify-center gap-10 items-center py-10 px-6"
            >
                <div>
                    <radial-progress-bar
                        :diameter="width / 3"
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
                    <div class="font-bold text-5xl text-gray-700 text-center">
                        {{ progress < 100 ? "Uploading files" : "Files Uploaded" }}
                    </div>
                    <div class="text-3xl">{{ progress }}%</div>
                    <div class="text-center text-2xl p-5 bg-gray-200">
                        {{ progress < 100 ? uploadingText : uploadedText }}
                    </div>
                    <div
                        class="mt-5 text-sky-600 text-2xl hover:cursor-pointer"
                        @click="cancelUploading"
                        v-if="progress < 100"
                    >
                        Cancel upload
                    </div>
                    <div
                        class="mt-5 text-sky-600 text-2xl hover:cursor-pointer"
                        @click="showModal"
                        v-else
                    >
                        Upload More
                    </div>
                </div>
            </div>
            <div class="border-t-gray-200 border-t-2 border-b-2 h-14">
                <div
                    class="h-full flex flex-row justify-between px-4 text-gray-400 text-lg"
                >
                    <div
                        class="flex flex-row gap-3 h-full items-center hover:cursor-pointer"
                    >
                        <ArrowTopRightOnSquareIcon
                            class="h-6 w-6 rotate-90"
                            aria-hidden="true"
                        />
                        <div
                            class="uppercase text-grey-200 font-bold"
                            @click="showModal"
                        >
                            Show Modal
                        </div>
                    </div>
                    <div
                        class="flex flex-row h-full items-center hover:cursor-pointer"
                    >
                        <InformationCircleIcon
                            class="h-8 w-8 text-gray-500"
                            aria-hidden="true"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, toRefs, ref, onMounted, computed } from "vue";
import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import Button from "@/Jetstream/Button.vue";

import RadialProgressBar from "vue3-radial-progress";

import {
    ArrowTopRightOnSquareIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/outline";

import { useStore } from "vuex";

const uploadingText =
    "We're uploading your files to the main server. Feel free to work on your tasks in the meantime.";
const uploadedText = "Upload completed";

const store = useStore();
const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb
const uppyShow = ref(true);
const UploadFormUppyRef = ref();

const props = defineProps({
    width: {
        type: Number,
        required: false,
        default: 320
    },
    baseFolder: {
        type: Object,
        required: false,
    },
});

const progress = computed({
    get() {
        return store.state.Uppy.files.progress;
    },
});

const uppyUploading = computed({
    get() {
        return store.state.Uppy.files.uploading;
    },
    set(val) {
        store.dispatch("updateFilesData", { uploading: val });
    },
});

const UppyState = computed({
    get() {
        return store.state.Uppy.uppy;
    },
    set(val) {
        store.dispatch("updateFilesData", { uppy: val });
    },
});

const onUploaded = (data) => {
    console.log('log here 1')
    this.$emit("uploaded", data);
};

const delay = (time) => {
    return new Promise((resolve) => setTimeout(resolve, time));
};

const onBeforeUploadUppy = async ({ files, state }) => {
    store.dispatch("updateFilesData", {
        show: true,
        uppy: state,
        viewMode: "min",
    });
    uppyShow.value = false;
    await delay(100);
    store.dispatch("updateFilesData", { uploading: true });
};

const showModal = () => {
    store.dispatch("updateFilesData", { viewMode: "med" });
};

const handleProgress = () => {};

const uploadProgress = () => {};

const uploadModalStart = () => {
    store.dispatch("updateFilesData", { uploading: true });
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

const cancelUploading = () => {
    uppyUploading.value = false;
};
</script>

<style lang="scss" scoped>
.uploading-description {
    max-width: 400px;
}

</style>
