<template>
    <Tree
        ref="tree"
        :value="tree"
        v-slot="{ node, index, path, tree }"
        draggable
        edgeScroll
        foldAllAfterMounted
        @drop="ondrop"
        @before-drop="onBeforeDrop"
        :rootNode="{$droppable: false}"
        @change="handleTreeChange"
    >
        <div class="relative z-0"> 
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
                        <ChevronRightIcon  @click="onItemClick(node)" v-if="node.$folded" class="text-gray-800 w-5" aria-hidden="true" />
                        <ChevronDownIcon  v-if="!node.$folded" class="text-gray-800 w-5" aria-hidden="true" />
                    </b>
                    <FolderIcon v-if="node.type === 'directory'" class="text-gray-500 w-5" aria-hidden="true" />
                    <DocumentTextIcon v-if="node.type === 'file'" class="text-gray-500 w-4" aria-hidden="true" />
                    <div @click="onFolderClick(node, tree, path)" class="text-gray-800">
                        {{ node.name }}
                    </div>
                </div>
                <div class="flex flex-row">
                    <PencilIcon class="text-gray-500 w-4" aria-hidden="true" />
                    <PlusSmIcon
                        class="text-red-500 w-6 rotate-45"
                        aria-hidden="true"
                    />
                </div>
            </div>
            <div v-if="node.loading" class="absolute backdrop-blur top-0 left-0 flex justify-center items-center z-10 w-full h-full">
                <svg role="status" class="inline w-6 h-6 text-gray-200 animate-spin dark:text-gray-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="#1e82a1"/>
                </svg>
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
        onItemClick(node) {
            this.$emit('itemClick', node)
        },
        onFolderClick(node, tree, path) {
            if (node.type === 'directory') {
                tree.toggleFold(node, path)
            }
            this.onItemClick(node)
        },
        ondrop(tree) {
          console.log('ondrop', tree)  
        },
        onBeforeDrop(tree) {
            console.log('onBeforeDrop', tree)  
        },
        handleTreeChange () {
            const store = this.$refs.tree.treesStore.store
            const {dragNode} = store
            const targetNode = this.$refs.tree.getNodeParentByPath(store.targetPath)
            // get the parent of dragNode
            // this.$refs.tree.getNodeParentByPath(store.startPath)
            console.log('drag', dragNode)
            console.log('target', targetNode)
        }
    }
};
</script>

<style lang="scss" scoped>
.tree-item {
    color: black;
}
</style>
