<template>
    <Dialog
        as="div"
        class="z-10 fixed bottom-0 right-0"
        @close="show = true"
        :open="show"
    >
        <ModalHeader
            :show="true"
            :progress="progress"
            @changeView="changeViewModal"
            :view="view"
            :title="title"
        />

        <DialogPanel
            class="pointer-events-auto w-screen max-w-2xl size-preview"
            :class="{ ['mini-preview']: view === 'min' }"
            v-show="view !== 'min'"
        >
            <div
                class="flex flex-col overflow-y-auto bg-white shadow-xl h-full"
                :class="{ ['mini-preview']: view === 'min' }"
            >
                <div v-if="false"
                    class="border-t-2 border-dotted border-gray-300 mb-6"
                ></div>

                <div class="relative flex-1">
                    <div class="absolute inset-0 h-48">
                        <UploadFormUppy
                            pid="3"
                            @uploaded="onUploaded"
                            :dashboardHeight="600"
                            :maxFileSize="maxFileSize"
                            @handleProgress="onHandleProgress"
                            @uploadProgress="onUploadProgress"
                            :title="title"
                        />
                    </div>
                </div>
            </div>
        </DialogPanel>
    </Dialog>
</template>

<script setup>
import { ref } from "vue";
import { Dialog, DialogPanel } from "@headlessui/vue";
import { MinusIcon } from "@heroicons/vue/outline";

import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import ModalHeader from "./ModalHeader.vue";

const show = ref(true);
const view = ref("med");
const progress = ref(0);
const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb

const props = defineProps({
    title: {
        type: String,
        default: 'Upload Files',
    }
});

const changeViewModal = (type) => {
    view.value = type;
};

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
.size-preview {
    height: 600px;
    width: 500px;
}
.mini-preview {
    height: 50px;
    width: 500px;
}

</style>
