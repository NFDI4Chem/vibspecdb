<template>
    <div>
        <treeselect
            v-model="treeValue"
            :multiple="true"
            :options="elements"
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
        value: {
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
        elements: {
            get() {
                return this.value;
            },
            set(value) {
                this.$emit("input", value);
            },
        },
    },
};
</script>
