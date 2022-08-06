<template>
    <Dialog
        as="div"
        class="z-10 fixed bottom-0 right-0"
        @close="open = true"
        :open="true"
    >
        <div
            class="flex justify-between items-center mini-preview px-6 bg-white"
            v-show="mini"
        >
            <radial-progress-bar
                :diameter="40"
                :innerStrokeWidth="6"
                :strokeWidth="4"
                :completed-steps="progress"
                innerStrokeColor="#ccd1d3"
                startColor="#1c7591"
                stopColor="#0d9ecb"
                :total-steps="100"
            >
              <div class="text-sm">100%</div>
            </radial-progress-bar>
            <button
                @click="showModal"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                <span class="sr-only">Show panel</span>
                <ArrowsExpandIcon class="h-10 w-10" aria-hidden="true" />
            </button>
        </div>

        <DialogPanel
            class="pointer-events-auto w-screen max-w-2xl size-preview"
            :class="{ ['mini-preview']: mini }"
            v-show="!mini"
        >
            <div
                class="flex flex-col overflow-y-auto bg-white py-6 shadow-xl size-preview"
                :class="{ ['mini-preview']: mini }"
            >
                <div class="px-4 sm:px-6">
                    <div class="flex items-start justify-between">
                        <DialogTitle class="text-lg font-medium text-gray-900">
                            Panel title
                        </DialogTitle>
                        <div class="ml-3 flex h-7 items-center gap-2">
                            <button
                                @click="miniModal"
                                type="button"
                                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <span class="sr-only">Hide panel</span>
                                <MinusIcon class="h-6 w-6" aria-hidden="true" />
                            </button>
                            <button
                                v-if="mini"
                                @click="showModal"
                                type="button"
                                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                <span class="sr-only">Show panel</span>
                                <ArrowsExpandIcon
                                    class="h-6 w-6"
                                    aria-hidden="true"
                                />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="relative mt-6 flex-1 px-4 sm:px-6">
                    <div class="absolute inset-0 px-4 sm:px-6 h-48">
                        <!-- <div class="h-full border-2 border-dashed border-gray-200" aria-hidden="true" /> -->
                        <UploadFormUppy
                            pid="3"
                            @uploaded="onUploaded"
                            :dashboardHeight="600"
                            :maxFileSize="5000000000"
                            @handleProgress="onHandleProgress"
                        />
                    </div>
                </div>
            </div>
        </DialogPanel>
    </Dialog>
</template>

<script setup>
import { ref } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { MinusIcon, ArrowsExpandIcon } from "@heroicons/vue/outline";

import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import RadialProgressBar from "vue3-radial-progress";

const open = ref(true);
const mini = ref(false);
const progress = ref(1);

// const props = defineProps({
//   open: Boolean
// })

console.log("created, modal");

const miniModal = () => {
    mini.value = true;
};
const showModal = () => {
    mini.value = false;
};

const onUploaded = (data) => {
    console.log("onUploaded", data);
};

const onHandleProgress = (prog) => {
  progress.value = prog;
}

</script>

<style lang="scss" scoped>
.size-preview {
    height: 700px;
    width: 500px;
}
.mini-preview {
    height: 50px;
    width: 500px;
}
</style>
