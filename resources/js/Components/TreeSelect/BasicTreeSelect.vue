<template>
    <div>
        <treeselect
            v-model="treeValue"
            :multiple="true"
            :options="list"
            :default-expand-level="1"
            :alwaysOpen="true"
            noChildrenText="No Sub-Folders"
        />
    </div>
</template>

<script>
import Treeselect from "vue3-treeselect";
import "vue3-treeselect/dist/vue3-treeselect.css";

export default {
    props: {
        elements: {
            required: false,
            type: Array,
            default: [],
        },
    },
    components: { Treeselect },
    data() {
        return {
            treeValue: null,
        };
    },
    computed: {
        list: {
            get() {
                return this.elements;
            },
            set(value) {
                this.$emit("update:elements", value);
            },
        },
    },
};
</script>
