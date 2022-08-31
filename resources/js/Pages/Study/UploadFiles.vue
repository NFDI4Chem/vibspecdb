<template>
    <div>
        <study-content :project="project" :study="study" current="Upload Files">
            <template #study-section>
                <div class="divide-y divide-gray-200 sm:col-span-9">
                    <div v-if="files">
                        <nav
                            v-if="selectTreeFolder"
                            class="flex p-3 w-full overflow-hidden cursor-pointer select-none"
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
                            class="flex-1 border-t border-gray-200 xl:flex px-1 py-3 height-limited"
                        >
                            <aside class="py-1 px-4" v-if="treeFilled">
                                <div
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
                                        />
                                    </div>
                                </div>
                            </aside>
                            <section
                                class="min-w-0 px-5 py-5 flex-1 flex flex-col overflow-y-auto lg:order-last h-full xl:border-l border-gray-200"
                                ref="sectionRef"
                            >
                                <div
                                    class="flex flex-col gap-2 text-gray-500h-full items-left pt-1"
                                >
                                    <div class="text-lg flex flex-col gap-1">
                                        <div
                                            class="flex flex-row flex-wrap gap-1 justify-between items-center"
                                        >
                                            <div>
                                                Uploading files to the folder:
                                            </div>
                                            <Popper
                                                placement="bottom"
                                                openDelay="50"
                                                arrow
                                                class="light-popper"
                                            >
                                                <InformationCircleIcon
                                                    class="h-6 w-6 text-gray-500"
                                                />
                                                <template #content>
                                                    <div>
                                                        <div
                                                            class="flex justify-center items-center h-6 bg-gray-500 text-white font-bold py-0 px-2 rounded mb-4"
                                                        >
                                                            File Uploader Info
                                                        </div>
                                                        <div>
                                                            File Uploader will
                                                            upload files to the
                                                            <strong
                                                                >selected
                                                                folder</strong
                                                            >
                                                            from the
                                                            <em>Files Tree</em>.
                                                        </div>
                                                        <div class="mt-2">
                                                            In case where no
                                                            folder is selected,
                                                            a root folder of the
                                                            Study will be used
                                                            with the Sign:
                                                            <strong
                                                                class="flex flex-row justify-center items-center gap-2 bg-gray-100 mt-2"
                                                            >
                                                                <CollectionIcon
                                                                    class="h-5 w-5 text-gray-500"
                                                                />/</strong
                                                            >
                                                        </div>
                                                    </div>
                                                </template>
                                            </Popper>
                                        </div>
                                        <div
                                            class="flex flex-row gap-3 items-center"
                                        >
                                            <CollectionIcon
                                                class="h-4 w-4 text-gray-500"
                                            />
                                            <strong
                                                class="flex items-center text-sm text-gray-600 force-wrap"
                                                >{{ selectTreeFolder }}</strong
                                            >
                                        </div>
                                    </div>
                                    <Uploader
                                        :width="sectionWidth"
                                        :baseFolder="activeItem"
                                        @uploaded="onUploaded"
                                    />
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
import { useForm, usePage } from "@inertiajs/inertia-vue3";

import { useFiles } from "@/VueComposable/useFiles";

import { ref, computed, onMounted, reactive, watch } from "vue";
import { useStore } from "vuex";

const props = defineProps(["study", "project", "files"]);
const { showChildsAPI } = useFiles();

const selectTreeItem = ref();
const selectTreeFolder = ref("/");
const store = useStore();

const treeFilled = computed(() => {
    return props?.files?.length > 0 && props?.files[0].children?.length > 0;
});

// console.log('files', props?.files)

const treeOptions = {
    checkable: false,
    deleteable: true,
    editable: true,
    createable: true,
    draggable: false,
    showInfo: true,
    showTitle: true,
    title: 'Files Tree',
};

const sectionRef = ref();
const sectionWidth = ref();

const activeItem = computed({
    get() {
        return store.getters.Treefiles.activeItem;
    },
    set(val) {
        store.dispatch("updateFilesTreeData", { activeItem: val });
    },
});


const FilesUploaded = computed({
    get() {
        return store.state.Uppy.files.uploaded;
    },
    set(val) {
        store.dispatch("updateFilesData", { uploaded: val });
    },
});

const study = computed(() => usePage().props.value.study);

watch(FilesUploaded, (newValue, oldValue) => {
    if (newValue) {
        if (activeItem.value.id === 0) {
            MakeReload();
        } else {
            showChildsAPI(activeItem.value);
        }
    }
});

const MakeReload = () => {
    const form = useForm({
        email: null,
    });
    form.post(route("study.file-upload.update", study.value.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

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

const TreeItemClick = (file, parent) => {
    const itemData = file.type === "directory" ? file : parent;
    displaySelected(itemData);
    storeSelected(itemData);
};

const storeSelected = (file) => {
    activeItem.value = file;
};

const onUploaded = () => {
    console.log("files uploaded (UploadFiles.vue)");
};


const displaySelected = (file) => {
    selectTreeItem.value = file;

    let sFolder = "/";
    if (selectTreeItem.value.name == "/") {
        sFolder = "/";
    } else {
        if (selectTreeItem.value.type != "file") {
            sFolder = selectTreeItem.value.relative_url;
        } else {
            sFolder =
                selectTreeItem.value.parent_id == null
                    ? "/"
                    : selectTreeItem.value.relative_url.replace(
                          "/" + selectTreeItem.value.name,
                          ""
                      );
        }
    }

    selectTreeFolder.value = sFolder;

    if (file.has_children && file.level > 0 && !file.children) {
        showChildsAPI(file);
    }
};
</script>

<style lang="scss" scoped>
.aside-menu {
    min-width: 300px;
}

.force-wrap {
    -ms-word-break: break-all;
    word-break: break-all;
    white-space: pre-wrap;
}
</style>
