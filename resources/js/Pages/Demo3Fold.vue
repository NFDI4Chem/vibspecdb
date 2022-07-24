<template>
    <Tree :value="treeData" v-slot="{ node, index, path, tree }" draggable>
        <span>
            <input
                type="checkbox"
                :checked="node.$checked"
                @change="tree.toggleCheck(node, path)"
            />
            {{ node.text }}
        </span>
    </Tree>
</template>
<script>
import "he-tree-vue/dist/he-tree-vue.css";
import { Tree, Fold, Draggable, Check } from "he-tree-vue";

export default {
    components: { Tree: Tree.mixPlugins([Fold, Draggable, Check]) },
    data() {
        return {
            treeData: [
                { text: "node 1" },
                { text: "node 2", children: [{ text: "node 2-1" }] },
                { text: "node 3" },
                { text: "node 4" },
                { text: "node 5" },
                { text: "node 6" },
            ],
        };
    },
};
</script>
