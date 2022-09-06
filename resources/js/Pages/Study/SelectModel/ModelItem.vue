<template>
    <div
        class="max-w-sm group relative flex flex-col overflow-hidden rounded-md border border-gray-200 bg-white"
        :class="{
            'pointer-events-none bg-gray-200': model?.disabled,
        }"
        @click="onSelect(model)"
        :title="simple_tooltip"
    >
        <div
            v-if="model.disabled"
            title="Model is not active"
            class="absolute border border-red-400 bg-red-400 font-bold right-0 top-0 text-sm p-0.5 px-1 text-red-800"
        >
            Disabled
        </div>
        <div
            v-if="model.id === active?.id"
            class="absolute border border-teal-600 bg-teal-600 font-bold right-0 bottom-0 text-sm p-0.5 px-1 text-gray-100"
        >
            Selected
        </div>
        <div
            class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none"
        >
            <img
                :src="model.imageSrc"
                :alt="model.imageAlt"
                class="h-full w-full object-cover object-center sm:h-full sm:w-full"
            />
        </div>
        <div class="flex flex-1 flex-col space-y-2 p-4 pb-8">
            <h3
                class="text-sm font-medium text-gray-900"
                :class="{ 'font-bold': model?.id === active?.id }"
            >
                <a :href="model.href">
                    <span aria-hidden="true" class="absolute inset-0" />
                    {{ model.name }}
                </a>
            </h3>
            <p class="text-sm text-gray-500">
                {{ model.description }}
            </p>
        </div>
    </div>
</template>

<script setup>

import { computed } from 'vue'

const props = defineProps(["model", "active"]);
const emit = defineEmits(["onSelect"]);

const simple_tooltip = computed(() => {
    if (props?.model.id === props?.active?.id) {
        return 'A selected Model for a job';
    } else if (props?.model.disabled) {
        return 'The model is disabled';
    } else {
        return 'Select model'
    }
})

const onSelect = (model) => {
  emit('onSelect', model)
}

</script>

<style lang="scss" scoped></style>
