<template>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl py-2 px-4 sm:py-4 sm:px-6 lg:max-w-7xl lg:px-8"
        >
            <h2 class="sr-only">Model Items</h2>

            <div
                class="grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6 sm:gap-y-10 lg:grid-cols-3 lg:gap-x-8"
            >
                <ModelItem
                    v-for="item in items"
                    :key="item?.id"
                    :model="item"
                    :active="active"
                    @onSelect="onSelect"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import ModelItem from "@/Pages/Study/SelectModel/ModelItem.vue";

const props = defineProps(["items", "active"]);
const emit = defineEmits(["onSelect"]);

const onSelect = (model) => {
    emit("onSelect", model);
};
</script>

<style lang="scss" scoped></style>
