<template>
    <div class="flex-1 min-h-fit  flex-row h-full">
        <div class="p-6 pt-0 px-0 flex-1 flex flex-col overflow-y-auto lg:order-last">
            <div v-if="files?.length" class="flex-1 flex-row">
                <div class="flex justify-start p-2 pr-5 pl-0 flex-row">
                    <div class="font-bold">1) Selected files:</div>
                </div>
                <FilesTable :files="files" @onShowDetails="onShowDetails" />
            </div>
            <div v-else>
                <div class="text-red-300">No files selected</div>
            </div>
        </div>
        <div class="p-6 px-0 flex-1 flex flex-col overflow-y-auto lg:order-last">
            <div v-if="model?.id > 0" class="flex-1 flex-row pb-10 border-b border-gray-500">
                <div class="flex justify-start p-2 px-5 pl-0 flex-row border-t border-gray-500 pt-8">
                    <div class="font-bold">2) Selected Model:</div>
                    <div class="font-lg px-5 font-bold text-teal-800">
                        {{ model?.name }}
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row gap-10">
                    <ModelItem
                        :model="model"
                        :active="{}"
                        @onSelect="() => {}"
                        class="mt-5"
                    />
                    <ModelInfo
                        :model="model"
                        class="grow mx-auto  py-2 px-4 sm:py-4 sm:px-6 lg:max-w-7xl lg:px-8"
                    />
                </div>
            </div>
            <div v-else>
                <div class="text-red-300">No models selected</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import FilesTable from "@/Pages/Study/Files/FilesTable.vue";
import ModelItem from "@/Pages/Study/SelectModel/ModelItem.vue";
import ModelInfo from "@/Pages/Study/SelectModel/ModelInfo.vue";

const props = defineProps(["files", "model"]);
const emit = defineEmits(["onShowDetails"]);

const onShowDetails = (rowId) => {
    emit("onShowDetails", rowId);
};
</script>

<style lang="scss" scoped></style>
