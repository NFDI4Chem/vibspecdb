<template>
    <div
        v-if="show"
        as="div"
        class="z-20 fixed bottom-0 right-0"
        @close="show = true"
        :open="show"
        :class="{
            ['mini-preview']: view === 'min',
            ['med-preview']: view === 'med',
            ['max-preview']: view === 'max',
        }"
    >
        <ModalHeader
            :show="true"
            :progress="progress"
            @changeView="changeViewModal"
            @closeView="closeViewModal"
            :view="view"
            :title="combinedTitle"
            class="modal-header"
        />

        <!-- <button @click="getUppyStatus">Get It</button> -->

        <div
            class="pointer-events-auto"
            v-show="view !== 'min'"
            :class="{
                ['mini-preview']: view === 'min',
                ['med-preview']: view === 'med',
                ['max-preview']: view === 'max',
            }"
        >
            <div
                class="flex flex-col overflow-y-auto bg-white shadow-xl h-full w-full"
            >
                <div class="relative flex-1">
                    <div class="absolute inset-0 h-full">
                        <UploadFormUppy
                            pid="3"
                            @uploaded="onUploaded"
                            :maxFileSize="maxFileSize"
                            @handleProgress="onHandleProgress"
                            @uploadProgress="onUploadProgress"
                            @onBeforeUpload="onBeforeUpload"
                            @onBeforeRetry="onBeforeRetry"
                            :title="title"
                            ref="UploadFormUppyRef"
                            @mounted="onUppyMounted"
                            :state="uppyState"
                            :stopUpload="false"
                            dashboardLimitText="Images only, up to 5 Gb (TODO: change limits here)"
                            :baseFolder="activeItem"
                        />
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from "vue";
import { Dialog, DialogPanel } from "@headlessui/vue";
import { MinusIcon } from "@heroicons/vue/24/outline";

import { useForm, usePage } from "@inertiajs/inertia-vue3";

import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import ModalHeader from "./ModalHeader.vue";

import { useStore } from "vuex";
import { useFiles } from "@/VueComposable/useFiles";


const { create } = useFiles();

const store = useStore();

// const show = ref(true);
// const view = ref("med");
const progress = ref(0);
const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb
const UploadFormUppyRef = ref();

const show = computed({
    get() {
        return store.state.Uppy.files.show;
    },
    set(val) {
        store.dispatch("updateFilesData", { show: val });
    },
});

const activeItem = computed({
    get() {
        return store.getters.Treefiles.activeItem;
    },
});

const view = computed({
    get() {
        return store.state.Uppy.files.viewMode;
    },
    set(val) {
        store.dispatch("updateFilesData", { viewMode: val });
    },
});

const uppyState = computed({
    get() {
        // console.log('Files Modal changes get')
        return store.state.Uppy.files.uppy;
    },
    set(val) {
        // console.log('Files Modal changes', val)
        // store.dispatch("updateFilesData", {uppy: val});
    },
});

const uploaded = computed({
    get() {
        return store.state.Uppy.files.uploaded;
    },
    set(val) {
        store.dispatch("updateFilesData", { uploaded: val });
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

watch(uppyUploading, (newValue, oldValue) => {
    if (newValue) {
        UploadFormUppyRef.value.upload();
    } else {
        closeViewModal();
    }
});

const props = defineProps({
    title: {
        type: String,
        default: "Upload Files",
    },
});

const combinedTitle = computed(() => {
    return (!uploaded.value) ? `Uploading ...` : `Files Uploaded`;
})

const onUppyMounted = () => {
    updateUppySize("med");
    // store.dispatch("updateFilesData", { uploaded: false });
    uploaded.value = false;
};

const updateUppySize = (type) => {
    let size = { width: 0, height: 0 };
    switch (type) {
        case "min":
            size = { width: 0, height: 0 };
            break;
        case "med":
            size = { width: 500, height: 600 };
            break;
        case "max":
            size = {
                width: window.innerWidth,
                height: window.innerHeight - 50,
            };
            break;
        default:
            size = { width: 0, height: 0 };
            break;
    }
    UploadFormUppyRef.value.setUppySize(size);
};

const changeViewModal = (type) => {
    updateUppySize(type);
    view.value = type;
    store.dispatch("updateFilesData", { viewMode: type });
};

const closeViewModal = () => {
    show.value = false;
    store.dispatch("updateFilesData", { uploading: false, progress: 0 });
    UploadFormUppyRef.value.cancelAll();
};

const onAddFile = async (file) => {
    await delay(500);
    const form = useForm(file)
        form.transform((data) => {
            const { name, type: ftype, size, id: uppyid} = data
            const type = 'file'
            const { project_id, study_id, level, base_id } = data?.meta || {}

            return {
                name, type,
                ftype, size, uppyid,
                project_id: parseInt(project_id),
                study_id: parseInt(study_id),
                level: parseInt(level) + 1,
                parent_id: parseInt(base_id)
            }
        })
        .post(route("files.create"), {
        preserveScroll: true,
        onSuccess: (file) => {
            // node.loading = false;
        },
    })
};

const delay = (time) => {
    return new Promise((resolve) => setTimeout(resolve, time));
};

const onUploaded = (file, data) => {
    onAddFile(file)
    uploaded.value = true
}

const onBeforeRetry = () => {
    // store.dispatch("updateFilesData", { uploaded: false });
    uploaded.value = false;
};

const onBeforeUpload = () => {
    // store.dispatch("updateFilesData", { uploaded: false });
    uploaded.value = false;
};

const onHandleProgress = (prog) => {
    progress.value = prog;
    store.dispatch("updateFilesData", { progress: prog });
};

const onUploadProgress = (file, { uploader, bytesUploaded, bytesTotal }) => {
    //   console.log('onUploadProgress', uploader, bytesUploaded, bytesTotal)
};

const getUppyStatus = () => {
    console.log("test", store.state.Uppy.files.uppy);
    UploadFormUppyRef.value.setUppyState(store.state.Uppy.uppy);
    view.value = "max";
    updateUppySize("max");
};

// defineExpose({ UploadFormUppyRef })
</script>

<style lang="scss" scoped>
.max-preview {
    width: 100vw;
    height: calc(100vh);
}
.med-preview {
    height: 650px;
    min-width: 300px;
}
.mini-preview {
    height: 50px;
    width: 350px;
}
</style>
