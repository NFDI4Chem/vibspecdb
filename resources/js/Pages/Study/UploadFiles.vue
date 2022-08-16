<template>
    <div>
        <study-content :project="project" :study="study" current="Upload Files">
            <template #study-section>
                <div class="divide-y divide-gray-200 sm:col-span-9">
                    <div v-if="files">
                        <nav
                            v-if="selectTreeFolder"
                            class="flex p-3"
                            aria-label="Breadcrumb"
                        >
                            <ol role="list" class="flex items-center space-x-2">
                                <li>
                                    <div>
                                        <a
                                            class="text-gray-400 hover:text-gray-900"
                                        >
                                            <HomeIcon
                                                class="flex-shrink-0 h-5 w-5"
                                            />
                                            <span class="sr-only">Home</span>
                                        </a>
                                    </div>
                                </li>
                                <li
                                    v-for="page in selectTreeFolder.split('/')"
                                    :key="page.name"
                                >
                                    <div
                                        v-if="page != ''"
                                        class="flex items-center"
                                    >
                                        <ChevronRightIcon
                                            class="flex-shrink-0 h-5 w-5 text-gray-400"
                                        />
                                        <a
                                            class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700"
                                            :aria-current="
                                                page ? 'page' : undefined
                                            "
                                            >{{ page }}</a
                                        >
                                    </div>
                                </li>
                            </ol>
                        </nav>
                        <div
                            class="min-w-0 flex-1 min-h-fit border-t border-gray-200 lg:flex px-1"
                        >
                            <aside class="py-1 px-4">
                                <div
                                    class="mr-5 flex flex-row justify-between items-center py-2.5 gap-2"
                                >
                                    <div class="font-bold">Files Tree</div>
                                    <Popper
                                        placement="bottom"
                                        openDelay="50"
                                        arrow
                                        :content="helpers?.FileTreeHelper"
                                        class="light-popper"
                                    >
                                        <InformationCircleIcon
                                            class="h-6 w-6 text-gray-500"
                                        />
                                    </Popper>
                                </div>
                                <div
                                    v-if="treeFilled"
                                    class="aside-menu relative flex flex-col border-r border-gray-200 overflow-y-auto"
                                >
                                    <UniFilesTree
                                        @itemClick="displaySelected"
                                        class="mr-5 min-w-min"
                                        :tree="files"
                                        :options="treeOptions"
                                        :onRemoveItem="onRemoveItem"
                                        :onAddChildren="onAddChildren"
                                    />
                                    <!-- {{files}} -->
                                </div>
                            </aside>
                            <section
                                class="min-w-0 px-5 py-2 flex-1 flex flex-col overflow-y-auto lg:order-last"
                                ref="sectionRef"
                            >
                                <!-- <div class="flex flex-col gap-2 text-gray-500 border-b-2 h-10 items-left pt-1"> -->
                                <div
                                    class="flex flex-col gap-2 text-gray-500 h-10 items-left pt-1"
                                >
                                    <div class="text-lg flex flex-col gap-1">
                                        <div
                                            class="flex flex-row gap-1 justify-between items-center"
                                        >
                                            <div>
                                                Uploading files to the folder:
                                            </div>
                                            <Popper
                                                placement="bottom"
                                                openDelay="50"
                                                arrow
                                                :content="
                                                    helpers?.FileBrowserHelper
                                                "
                                                class="light-popper"
                                            >
                                                <InformationCircleIcon
                                                    class="h-6 w-6 text-gray-500"
                                                />
                                            </Popper>
                                        </div>
                                        <div
                                            class="flex flex-row gap-3 items-center"
                                        >
                                            <CollectionIcon
                                                class="h-5 w-5 text-gray-500"
                                            />
                                            <strong
                                                class="text-gray-600 force-wrap"
                                                >{{ selectTreeFolder }}</strong
                                            >
                                        </div>
                                    </div>
                                    <Uploader :width="sectionWidth" />
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </template>
        </study-content>
    </div>
</template>

<script setup>
import {
    ChevronRightIcon,
    HomeIcon,
    InformationCircleIcon,
    CollectionIcon,
} from "@heroicons/vue/solid";
import StudyContent from "@/Pages/Study/Content.vue";
import UniFilesTree from "@/Components/UniFilesTree/UniFilesTree.vue";
import Uploader from "@/Components/UploadForm/Uploader.vue";

import { ref, computed, onMounted, reactive } from "vue";

const props = defineProps(["study", "project", "files"]);

const selectTreeItem = ref();
const selectTreeFolder = ref("/");

const helpers = {
    FileBrowserHelper:
        "File browser will upload files to the folder selected at the right side. In case of no folder selected, a root folder of the Study will be used with the sing \/",
    FileTreeHelper: 
        "File tree browser is able to rename, move, delete files and create new folders. For quick workflow, internal folder files are loaded when the parent folder is opened."
};

const treeFilled = computed(() => {
    return props?.files?.length > 0 && props?.files[0].children?.length > 0;
});

const treeOptions = {
    checkable: false,
};

const sectionRef = ref();
const sectionWidth = ref();

onMounted(() => {
    sectionWidth.value = sectionRef.value.clientWidth;
});

const onRemoveItem = (tree, node, path) => {
    node.loading = true;
    axios.delete("/api/v1/files/delete/" + node.id).then((response) => {
        node.loading = false;
        tree.removeNodeByPath(path);
    });
};

const onAddChildren = (node) => {
    node.loading = true;
    axios.post("/api/v1/files/create", node).then((response) => {
        if (node.children) {
            node.children.push(response?.data);
        } else {
            node.children = [response?.data];
        }
        node.loading = false;
        node.$folded = false;
    });
};

const displaySelected = (file) => {
    //   file.$folded = true;
    selectTreeItem.value = file;

    let sFolder = "/";
    if (selectTreeItem.value.name == "/") {
        sFolder = "/";
    } else {
        if (selectTreeItem.value.type != "file") {
            sFolder = selectTreeItem.value.relative_url;
        } else {
            if (selectTreeItem.value.parent_id == null) {
                sFolder = "/";
            } else {
                sFolder =
                    "/" +
                    selectTreeItem.value.relative_url.replace(
                        "/" + selectTreeItem.value.name,
                        ""
                    );
            }
        }
    }

    selectTreeFolder.value = sFolder;

    if (file.has_children && file.level > 0 && !file.children) {
        file.loading = true;
        axios
            .get("/api/v1/files/children/" + props.study.id + "/" + file.id)
            .then((response) => {
                file.children = response.data.files[0].children;
                file.loading = false;
            });
    }
};
</script>

<style lang="scss" scoped>
.aside-menu {
    width: 500px;
    height: 50vh;
    overflow-x: auto;
}
.force-wrap {
    -ms-word-break: break-all;
    word-break: break-all;
    white-space: pre-wrap;
}
</style>
