<template>
    <div>
        <study-content :project="project" :study="study" current="Files">
            <template #study-section>
                <div class="divide-y divide-gray-200 sm:col-span-9">
                    <div v-if="files">
                        <div
                            class="min-w-0 flex-1 min-h-fit border-t border-gray-200 lg:flex"
                        >
                            <aside class="py-3 px-2">
                                <div
                                    v-if="treeFilled"
                                    class="aside-menu relative flex flex-col border-r border-gray-200 overflow-y-auto"
                                >
                                    <div class="mr-5 min-w-fit">
                                        <UniFilesTree
                                            @itemClick="TreeItemClick"
                                            :tree="files"
                                            :options="treeOptions"
                                            :onRemoveItem="onRemoveItem"
                                            :onAddChildren="onAddChildren"
                                            :activeItem="activeItem"
                                        />
                                    </div>
                                </div>
                            </aside>
                            <section
                                class="min-w-0 p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                            >
                                <!-- <Uploader /> -->
                                <SelectedFiles />
                            </section>
                        </div>
                    </div>
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>

import {ChevronRightIcon, HomeIcon} from "@heroicons/vue/solid";
import StudyContent from "@/Pages/Study/Content.vue";
import SelectedFiles from "@/Pages/Study/SelectedFiles.vue";

import UniFilesTree from "@/Components/UniFilesTree/UniFilesTree.vue"; 
import { useFiles } from "@/VueComposable/useFiles";


import { ref, computed, onMounted, reactive } from "vue";

const props = defineProps(["study", "project", "files"])
const { showChildsAPI } = useFiles();

const selectTreeFolder = ref("/");

const TreeItemClick = (file, parent) => {
    console.log('Files: file, parent')
    const itemData = file.type === "directory" ? file : parent;
    displaySelected(itemData);
}

const treeFilled = computed(() => {
    return props?.files?.length > 0 && props?.files[0].children?.length > 0;
});

const onRemoveItem = (tree, node, path) => {}
const onAddChildren = (node) => {}
const activeItem = computed(() => {
    return {}
});

const treeOptions = {
    checkable: true,
    deleteable: false,
    editable: false,
    createable: false,
    draggable: false,
    showInfo: false,
};

const displaySelected = (file) => {
    if (file.has_children && file.level > 0 && !file.children) {
        showChildsAPI(file);
    }
};



</script>

<style lang="scss" scoped>
.aside-menu {
    // width: 300px;
    min-width: 300px;
    height: 50vh;
    overflow-x: auto;
}


</style>
