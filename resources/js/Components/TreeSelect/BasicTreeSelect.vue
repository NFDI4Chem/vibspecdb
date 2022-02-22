<template>
    <div>
        <treeselect
            v-model="treeValue"
            :multiple="true"
            :options="list"
            :default-expand-level="1"
            :always-open="true"
            no-children-text="No Sub-Folders"
        />
    </div>
</template>

<script>
import Treeselect from "vue3-treeselect";
import "vue3-treeselect/dist/vue3-treeselect.css";

export default {
    components: { Treeselect },
    props: {
        elements: {
            required: false,
            type: Array,
            default: [],
        },
    },
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
