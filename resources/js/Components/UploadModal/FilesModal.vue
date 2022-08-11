<template>
    <Dialog
        as="div"
        class="z-10 fixed bottom-0 right-0"
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
            :title="title"
            class="modal-header"
        />

        <DialogPanel
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
                            :title="title"
                            ref="UploadFormUppyRef"
                            @mounted="onUppyMounted"
                        />
                    </div>
                </div>
            </div>
        </DialogPanel>
    </Dialog>
</template>

<script setup>
import { ref, onMounted  } from "vue";
import { Dialog, DialogPanel } from "@headlessui/vue";
import { MinusIcon } from "@heroicons/vue/outline";

import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import ModalHeader from "./ModalHeader.vue";

const show = ref(true);
const view = ref("med");
const progress = ref(0);
const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb

const UploadFormUppyRef = ref()

const props = defineProps({
    title: {
        type: String,
        default: "Upload Files",
    },
});


const onUppyMounted = () => {
  updateUppySize(view.value);
}

const updateUppySize = (type) => {
    let size = {width: 0, height: 0};
    switch(type) {
        case 'min':
            size = { width: 0, height: 0};
            break;
        case 'med':
            size = { width: 500, height: 600};
            break;
        case 'max':
            size = { width: window.innerWidth, height: window.innerHeight - 50};
            break;
        default:
            size = { width: 0, height: 0};
            break;
    }
    UploadFormUppyRef.value.setUppySize(size);
}


const changeViewModal = (type) => {
    updateUppySize(type);
    view.value = type;
};

const closeViewModal = () => {
    show.value = false;
}

const onUploaded = (data) => {
    console.log("onUploaded", data);
};

const onHandleProgress = (prog) => {
    progress.value = prog;
};

const onUploadProgress = (file, { uploader, bytesUploaded, bytesTotal }) => {
    //   console.log('onUploadProgress', uploader, bytesUploaded, bytesTotal)
};
</script>

<style lang="scss" scoped>

.max-preview {
    width: 100vw;
    height: calc(100vh - 50px);
}
.med-preview {
    height: 600px;
    width: 500px;
}
.mini-preview {
    height: 50px;
    width: 500px;
}
</style>
