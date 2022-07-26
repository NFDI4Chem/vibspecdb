<template>
    <Tree
        :value="tree"
        v-slot="{ node, index, path, tree }"
        draggable
        edgeScroll
        foldAllAfterMounted
    >
        <div
            class="flex justify-between gap-6 items-center whitespace-nowrap hover:cursor-pointer ml-2"
        >
            <div class="flex flex-row gap-2 items-center">
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        :checked="node.$checked"
                        @change="tree.toggleCheck(node, path)"
                    />
                </div>
                <b v-if="node.type === 'directory'" @click="tree.toggleFold(node, path)">
                    <ChevronRightIcon  @click="onFolderClick(node)" v-if="node.$folded" class="text-gray-800 w-5" aria-hidden="true" />
                    <ChevronDownIcon  v-if="!node.$folded" class="text-gray-800 w-5" aria-hidden="true" />
                </b>
                <FolderIcon v-if="node.type === 'directory'" class="text-gray-500 w-5" aria-hidden="true" />
                <DocumentTextIcon v-if="node.type === 'file'" class="text-gray-500 w-4" aria-hidden="true" />
                <div @click="onItemClick(node, tree, path)" class="text-gray-800">{{ node.name }}</div>
            </div>
            <div class="flex flex-row">
                <PencilIcon class="text-gray-500 w-4" aria-hidden="true" />
                <PlusSmIcon
                    class="text-red-500 w-6 rotate-45"
                    aria-hidden="true"
                />
            </div>
        </div>
    </Tree>
</template>

<script>
import "he-tree-vue/dist/he-tree-vue.css";
import { Tree, Fold, Draggable, Check } from "he-tree-vue";

import {
    PencilIcon,
    PlusSmIcon,
    FolderIcon,
    DocumentTextIcon,
    ChevronDownIcon,
    ChevronRightIcon,
} from "@heroicons/vue/solid";

export default {
    props: {
        tree: {
            type: Object,
            required: true,
        },
    },
    components: {
        Tree: Tree.mixPlugins([Fold, Draggable, Check]),
        PencilIcon,
        PlusSmIcon,
        FolderIcon,
        DocumentTextIcon,
        ChevronDownIcon,
        ChevronRightIcon,
    },
    data() {
        return {};
    },
    methods: {
        onFolderClick(node) {
            console.log('None', node)
            this.$emit('folderClick', node)
        },
        onItemClick(node, tree, path) {
            if (node.type === 'directory') {
                tree.toggleFold(node, path)
            }
            this.onFolderClick(node)
        }
    }
};
</script>

<style lang="scss" scoped>
.tree-item {
    color: black;
}
</style>
