<template>
    <div>
        <study-content :project="project" :study="study" current="Files">
            <template #study-section>
                <div class="flex flex-1 flex-col justify-between">
                    <div class="divide-y divide-gray-200 sm:col-span-9 h-full">
                        <div v-if="vueFiles?.length" class="h-full">
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
                                                :tree="vueFiles"
                                                :options="treeOptions"
                                                :onRemoveItem="onRemoveItem"
                                                :onAddChildren="onAddChildren"
                                                :activeItem="activeItem"
                                                @onCheck="onFilesTreeCheck"
                                                :checked="checkedFiles"
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
                                        <div class="text-red-300">No files selected</div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div v-else>
                            <div class="text-red-300 p-5">No files uploaded</div>
                        </div>
                    </div>
                    <Footer :steps="steps" />
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>
import StudyContent from "@/Pages/Study/Content.vue";
import FilesTable from "@/Pages/Study/Files/FilesTable.vue";
import Footer from "@/Pages/Study/Helpers/Footer.vue";

import UniFilesTree from "@/Components/UniFilesTree/UniFilesTree.vue";
import { useFiles } from "@/VueComposable/useFiles";
import { selectedFiles, onShowDetails } from "@/VueComposable/store";
import { currentStudyStep, StudySubmitSteps } from "@/VueComposable/store";

import { ref, computed, onMounted, reactive } from "vue";

const props = defineProps(["study", "project", "files"]);
const { showChildsAPI } = useFiles();

const vueFiles = computed(() => props.files);
const checkedFiles = computed(() => selectedFiles.value.map((f) => f.id));

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
                details: {},
            };
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

const onRemoveItem = () => {};
const onAddChildren = () => {};
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

currentStudyStep.value = 1;
const steps = computed(() => StudySubmitSteps(props?.study));
</script>

<style lang="scss" scoped>
.aside-menu {
    min-width: 300px;
    overflow-x: auto;
}
</style>
