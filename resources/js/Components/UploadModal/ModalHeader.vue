<template>
    <div
        class="flex justify-between items-center mini-preview px-2 bg-white"
        v-if="show"
    >
        <div class="flex justify-between items-center mini-preview bg-white gap-2">
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
          <div class="text-lg font-medium text-gray-900">{{title}}</div>
        </div>

        <div class="flex justify-between items-center mini-preview bg-white gap-2">
            <button
                v-if="view !== 'min'"
                @click="changeView('min')"
                title="Minimize window to bottom panel"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
            >
                <span class="sr-only">Hide panel</span>
                <MinusIcon class="h-8 w-8" aria-hidden="true" />
            </button>
            <button
                @click="changeView(view === 'med' ? 'max' : 'med')"
                type="button"
                class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
            >
                <span class="sr-only">Show panel</span>
                <ArrowsExpandIcon class="h-8 w-8" aria-hidden="true" />
            </button>
        </div>
    </div>
</template>

<script setup>
import RadialProgressBar from "vue3-radial-progress";
import { MinusIcon, ArrowsExpandIcon } from "@heroicons/vue/outline";

const props = defineProps({
    progress: Number,
    title: {
        type: String,
        default: 'Upload Files',
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

const emit = defineEmits(["changeView"]);

const changeView = (state) => {
    emit("changeView", state);
};
</script>

<style lang="scss" scoped></style>
