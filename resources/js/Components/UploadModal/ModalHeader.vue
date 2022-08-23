<template>
    <div
        class="flex justify-between items-center px-2 bg-white border-teal-500"
        :class="{
            ['border-t border-l']: view === 'min',
            ['border-t border-l border-b-gray border-b']: view === 'med',
            ['mini-header']: ['min', 'med'].includes(view),
            ['max-header border-b']: ['max'].includes(view),
        }"
        v-if="show"
    >
        <div class="flex justify-between items-center bg-white gap-2">
            <radial-progress-bar
                v-if="progress > 0"
                :diameter="40"
                :innerStrokeWidth="4"
                :strokeWidth="4"
                :completed-steps="progress"
                innerStrokeColor="#ccd1d3"
                startColor="#1c7591"
                stopColor="#0d9ecb"
                :total-steps="100"
            >
                <div class="text-[9px] pt-[1px] font-bold">{{ progress }}%</div>
            </radial-progress-bar>
            <div class="text-lg font-medium text-gray-900">{{ title }}</div>
        </div>

        <div class="flex justify-between items-center bg-white gap-2">
            <button
                v-if="view !== 'min'"
                @click="changeView('min')"
                title="Minimize window to bottom panel"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
            >
                <span class="sr-only">Hide panel</span>
                <MinusCircleIcon class="h-6 w-6" aria-hidden="true" />
            </button>
            <button
                v-if="['max', 'min'].includes(view)"
                @click="changeView('med')"
                title="Show medium size files upload modal"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
            >
                <span class="sr-only">Medium panel</span>
                <CreditCardIcon class="h-7 w-7" aria-hidden="true" />
            </button>
            <button
                v-if="view !== 'max'"
                @click="changeView('max')"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
            >
                <span class="sr-only">Show panel</span>
                <ArrowsExpandIcon class="h-6 w-6" aria-hidden="true" />
            </button>
            <button
                @click="closeModal()"
                type="button"
                class="ml-2 rounded-md bg-white text-red-300 hover:text-red-500 focus:outline-none"
            >
                <span class="sr-only">Close panel</span>
                <XCircleIcon class="h-6 w-6" aria-hidden="true" />
            </button>
        </div>
    </div>
</template>

<script setup>
import RadialProgressBar from "vue3-radial-progress";
import {
    MinusCircleIcon,
    ArrowsExpandIcon,
    XCircleIcon,
    CreditCardIcon,
} from "@heroicons/vue/outline";

const props = defineProps({
    progress: Number,
    title: {
        type: String,
        default: "Upload Files",
    },
    show: {
        type: Boolean,
        default: true,
    },
    view: {
        type: String,
        default: "med",
        validator: function (value) {
            return ["min", "med", "max"].includes(value);
        },
    },
});

const emit = defineEmits(["changeView", "closeView"]);

const changeView = (state) => {
    emit("changeView", state);
};

const closeModal = () => {
    emit("closeView");
};
</script>

<style lang="scss" scoped>
.mini-header {
    height: 50px;
    width: 500px;
}

.max-header {
    height: 50px;
    width: 100vw;
}
</style>
