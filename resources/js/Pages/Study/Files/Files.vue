<template>
    <div>
        <study-content :project="project" :study="study" current="Files">
            <template #study-section>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
                        <div v-if="files" class="h-full">
                            <div
                                class="min-w-0 flex-1 min-h-fit border-t border-gray-200 lg:flex h-full"
                            >
                                <aside class="py-3 px-2 pl-4">
                                    <div
                                        v-if="treeFilled"
                                        class="aside-menu relative flex flex-col overflow-y-auto"
                                    >
                                        <div class="mr-5 min-w-fit">
                                            <UniFilesTree
                                                @itemClick="TreeItemClick"
                                                :tree="files"
                                                :options="treeOptions"
                                                :onRemoveItem="onRemoveItem"
                                                :onAddChildren="onAddChildren"
                                                :activeItem="activeItem"
                                                @onCheck="onFilesTreeCheck"
                                            />
                                        </div>
                                    </div>
                                </aside>
                                <div
                                    class="border-r border-gray-200 min-h-fit my-3"
                                ></div>
                                <section
                                    class="min-w-0 p-6 flex-1 flex flex-col overflow-y-auto lg:order-last"
                                >
                                    <div v-if="selectedFiles.length > 0">
                                        <div class="font-bold">
                                            Selected files:
                                        </div>
                                        <FilesTable
                                            :files="selectedFiles"
                                            @onShowDetails="onShowDetails"
                                        />
                                    </div>
                                    <div v-else>
                                        <div>No files were selected</div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <Footer :steps="steps" />
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>
import { ChevronRightIcon, HomeIcon } from "@heroicons/vue/solid";
import StudyContent from "@/Pages/Study/Content.vue";
import FilesTable from "@/Pages/Study/Files/FilesTable.vue";
import Footer from "@/Pages/Study/Helpers/Footer.vue";

import UniFilesTree from "@/Components/UniFilesTree/UniFilesTree.vue";
import { useFiles } from "@/VueComposable/useFiles";
import { selectedFiles } from "@/VueComposable/store";

import { ref, computed, onMounted, reactive } from "vue";

const props = defineProps(["study", "project", "files"]);
const { showChildsAPI } = useFiles();

const selectTreeFolder = ref("/");

console.log('selectedFiles', selectedFiles)

const onFilesTreeCheck = (checked) => {
    selectedFiles.value = checked
        .filter((f) => f.type === "file")
        .map((f) => {
            return {
                id: f.id,
                name: f.name,
                updated_at: new Date(Date.parse(f.updated_at)).toLocaleString(
                    "de-DE",
                    { timeZone: "Europe/Berlin" }
                ),
                type: f.type,
                created_at: new Date(
                    Date.parse(f.created_at)
                ).toLocaleDateString("de-DE", { timeZone: "Europe/Berlin" }),
                relative_url: f.relative_url,
                details: {},
            };
        });
};

const onShowDetails = (rowId) => {
    selectedFiles.value = selectedFiles.value.map((item) => {
        return rowId === item.id
            ? {
                  ...item,
                  detailsOpen: !item?.detailsOpen,
              }
            : item;
    });
};

const TreeItemClick = (file, parent) => {
    // console.log("Files: file, parent");
    const itemData = file.type === "directory" ? file : parent;
    displaySelected(itemData);
};

const treeFilled = computed(() => {
    return props?.files?.length > 0 && props?.files[0].children?.length > 0;
});

const onRemoveItem = (tree, node, path) => {};
const onAddChildren = (node) => {};
const activeItem = computed(() => {
    return {};
});

const treeOptions = {
    checkable: true,
    deleteable: false,
    editable: false,
    createable: false,
    draggable: false,
    showInfo: false,
    showTitle: true,
    title: "Files Tree:",
};

const displaySelected = (file) => {
    if (file.has_children && file.level > 0 && !file.children) {
        showChildsAPI(file);
    }
};

const link_url = {
    files: `/studies/${props?.study?.id}/files`,
    models: `/studies/${props?.study?.id}/models`,
    jobs: `/studies/${props?.study?.id}/jobs`,
}

const steps = computed(() => [
    { name: "Step 1: select files to process", href: link_url.files, status: "current" },
    { name: "Step 2: select model to process", href: link_url.models, status: "" },
    { name: "Step 3: view job details", href: link_url.jobs, status: "" },
]);
</script>

<style lang="scss" scoped>
.aside-menu {
    min-width: 300px;
    overflow-x: auto;
}
</style>
