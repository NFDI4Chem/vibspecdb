<template>
    <div>
        <study-content :project="project" :study="study" current="Jobs">
            <template #study-section>
                Hello here
                <Footer :n="3" :active="2" />
            </template>
        </study-content>
    </div>
</template>

<script setup>
import StudyContent from "@/Pages/Study/Content.vue";
import Footer from "@/Pages/Study/Helpers/Footer.vue";

const props = defineProps(["study", "project"]);

</script>

<style lang="scss" scoped></style>
