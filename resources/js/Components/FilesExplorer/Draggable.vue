<template>
    <div>
        <nested :list="list" />
    </div>
</template>

<script>
import nested from "@/Components/FilesExplorer/Nested.vue";
export default {
    name: "Draggable",
    components: {
        nested,
    },
    props: ["list"],
};
</script>
