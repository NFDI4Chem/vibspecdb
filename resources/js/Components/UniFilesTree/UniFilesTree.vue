<template>
    <div class="flex flex-row justify-between items-center py-2.5 gap-2">
        <div class="font-bold" v-if="options.showTitle">{{options.title}}</div>
        <Popper placement="bottom" openDelay="50" arrow class="light-popper" v-if="options.showInfo">
            <InformationCircleIcon class="h-6 w-6 text-gray-500" />
            <template #content>
                <div>
                    <div
                        class="h-6 bg-gray-500 text-white font-bold px-2 rounded mb-4 text-center"
                    >
                        Files Tree Info
                    </div>
                    <div>
                        <div
                            class="flex flex-row items-center justify-left gap-0"
                            v-if="options.editable"
                        >
                            <div class="mr-2">
                                <strong>To rename</strong> item use:
                            </div>
                            <PencilIcon class="text-gray-500 w-4 h-4" />
                        </div>
                        <div
                            class="flex flex-row items-center justify-left gap-0"
                        >
                            <div class="mr-2">
                                <strong>To move</strong> item hold and move
                                icons:
                            </div>
                            <FolderIcon class="text-gray-500 w-4" />,
                            <DocumentTextIcon class="text-gray-500 w-4" />
                        </div>
                        <div
                            class="flex flex-row items-center justify-left gap-3"
                            v-if="options.deleteable"
                        >
                            <div class="mr-2">
                                <strong>To delete</strong> item use:
                            </div>
                            <TrashIcon class="text-red-400 w-4 h-4" />
                        </div>
                    </div>
                    <div class="italic mt-2">
                        For quick workflow folder files are loaded when the
                        parent folder is opened.
                    </div>
                </div>
            </template>
        </Popper>
    </div>

    <Tree
        ref="tree"
        :value="tree"
        v-slot="{ node, index, path, tree }"
        :draggable="options.draggable"
        :edgeScroll="true"
        :foldAllAfterMounted="false"
        @drop="ondrop"
        @before-drop="onBeforeDrop"
        :rootNode="{ $droppable: false, $draggable: true }"
        @change="handleTreeChange"
        class="files-tree"
    >
        <div
            class="relative z-0"
            :class="{ ['active-node']: node.id === activeItem.id }"
        >
            <div
                class="flex justify-between gap-2 items-center whitespace-nowrap ml-2"
            >
                <div
                    @click="onItemClick(node, path, tree)"
                    class="flex flex-row gap-2 items-center w-full"
                >
                    <div class="flex items-center" v-if="options.checkable">
                        <input
                            type="checkbox"
                            :checked="node.$checked"
                            @change="handleCheck(node, path, tree)"
                        />
                    </div>
                    <b v-if="node.type === 'directory'">
                        <ChevronRightIcon
                            @click="toggleFold(tree, node, path)"
                            v-if="node.$folded"
                            class="text-gray-800 w-5"
                        />
                        <ChevronDownIcon
                            @click="toggleFold(tree, node, path)"
                            v-if="!node.$folded"
                            class="text-gray-800 w-5"
                        />
                    </b>
                    <FolderIcon
                        v-if="node.type === 'directory'"
                        class="text-gray-500 w-5 active-field"
                    />
                    <DocumentTextIcon
                        v-if="node.type === 'file'"
                        class="text-gray-500 w-4 active-field"
                    />
                    <input
                        role="button"
                        v-model="node.name"
                        class="focus-visible:outline-none active-field w-full"
                        :class="{ ['text-teal-500']: node.edit}"
                        :readonly="node.edit ? false : 'readonly'"
                    />
                </div>
                <div class="flex flex-row">
                    <PlusSmallIcon
                        v-if="!node.edit && node.type === 'directory' && options.createable"
                        class="text-gray-500 w-6"
                        @click="addChildren(node)"
                    />
                    <PencilIcon
                        v-if="!node.edit && node.name !== '/' && options.editable"
                        @click="node.edit = true"
                        class="text-gray-500 w-4"
                    />
                    <CheckIcon
                        v-if="node.edit"
                        @click="
                            node.edit = false;
                            renameItem(node);
                        "
                        class="text-gray-500 w-4"
                    />
                    <PlusSmallIcon
                        v-if="node.edit"
                        class="text-gray-400 w-6 rotate-45 mr-[-2px]"
                        @click="node.edit = false"
                    />
                    <TrashIcon
                        v-if="!node.edit && node.name !== '/' && options.deleteable"
                        class="text-red-400 w-4 ml-2"
                        @click="removeItem(tree, node, path)"
                    />
                </div>
            </div>
            <div
                v-if="node.loading"
                class="absolute backdrop-blur top-0 left-0 flex justify-center items-center z-10 w-full h-full"
            >
                <svg
                    role="status"
                    class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600"
                    viewBox="0 0 100 101"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor"
                    />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="#1e82a1"
                    />
                </svg>
            </div>
        </div>
    </Tree>
</template>

<script>
import "he-tree-vue/dist/he-tree-vue.css";
import { Tree, Fold, Draggable, Check } from "he-tree-vue";

// const emit = defineEmits(["itemClick"]);

import {
    PencilIcon,
    PlusSmallIcon,
    FolderIcon,
    DocumentTextIcon,
    ChevronDownIcon,
    ChevronRightIcon,
    CheckIcon,
    TrashIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/solid";

export default {
    props: {
        tree: {
            type: Object,
            required: true,
        },
        options: {
            type: Object,
            required: false,
            default: () => {
                return {
                    checkable: false,
                    deleteable: true,
                    editable: true,
                    createable: true,
                    draggable: true,
                    showInfo: true,
                    title: 'Files Tree',
                }
            },
        },
        onRemoveItem: {
            type: Function,
            required: false,
            default: () => {},
        },
        onAddChildren: {
            type: Function,
            required: false,
            default: () => {},
        },
        checked: {
            type: Array,
            required: false,
            default: () => [],
        },
        activeItem: {
            type: Object,
            required: false,
            default: () => {
                return {
                    id: -1,
                };
            },
        },
    },
    components: {
        Tree: Tree.mixPlugins([Fold, Draggable, Check]),
        PencilIcon,
        PlusSmallIcon,
        FolderIcon,
        DocumentTextIcon,
        ChevronDownIcon,
        ChevronRightIcon,
        CheckIcon,
        TrashIcon,
        InformationCircleIcon,
    },
    data() {
        return {};
    },
    mounted() {
        this.setChecked();
    },
    methods: {
        onItemClick(node, path, tree) {
            const parent = this.$refs.tree.getNodeParentByPath(path);
            this.$emit("itemClick", node, parent);
        },
        handleCheck(node, path, tree) {
            tree.toggleCheck(node, path)
            const checked = this.getAllChecked(tree)
            this.$emit("onCheck", checked)
        },
        toggleFold(tree, node, path) {
            tree.toggleFold(node, path);
        },
        ondrop(tree) {
            console.log("ondrop", tree);
        },
        onBeforeDrop(tree) {
            console.log("onBeforeDrop", tree);
        },
        renameItem(node) {
            console.log(node);
        },
        removeItem(tree, node, path) {
            this.onRemoveItem(tree, node, path);
        },
        addChildren(node) {
            this.onAddChildren(node);
        },
        handleTreeChange() {
            const store = this.$refs.tree.treesStore.store;
            const { dragNode } = store;
            const targetNode = this.$refs.tree.getNodeParentByPath(
                store.targetPath
            );
            // get the parent of dragNode
            // this.$refs.tree.getNodeParentByPath(store.startPath)
            console.log("drag", dragNode)
            console.log("target", targetNode)
        },
        getAllChecked(tree){
            const checked = [];
            tree.walkTreeData((node) => {
                node.$checked && checked.push(node)
            })
            return checked
        },
        setChecked() {
            const tree = this.$refs.tree;
            tree.walkTreeData((node) => {
                if (this.checked.includes(node.id)) { node.$checked = true; }
            })
        }
    },
};
</script>

<style lang="scss" scoped>
.tree-item {
    color: black;
}
</style>

<style lang="scss">
.files-tree .tree-node {
    // padding: 0 !important;
    min-width: 300px;
}

.active-node .active-field {
    color: rgb(13, 138, 172);
    font-weight: bold;
}
</style>
