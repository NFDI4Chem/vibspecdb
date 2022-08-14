<template>
    <div>
        <study-content :project="project" :study="study" current="Jobs">
            <template #study-section>
                Hello here
            </template>
        </study-content>
    </div>
</template>

<script setup>
import StudyContent from "@/Pages/Study/Content.vue";

const props = defineProps(["study", "project"]);

</script>

<style lang="scss" scoped></style>
