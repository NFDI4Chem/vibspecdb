<template>
    <div>
        <study-content :project="project" :study="study" current="Jobs">
            <template #study-section>
                <UploadFormUppy
                    pid="3"
                    :maxFileSize="maxFileSize"
                    @uploaded="onUploaded"
                    @onBeforeUpload="onBeforeUploadUppy"
                />
                <Button @click="testAction">RunMe</Button>
                {{ listRecent }}
            </template>
        </study-content>
    </div>
</template>

<script>
import { reactive, toRefs } from "vue";
import StudyContent from "@/Pages/Study/Content.vue";
import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import Button from "@/Jetstream/Button.vue";

import { useStore } from 'vuex'

export default {
    components: {
        StudyContent,
        UploadFormUppy,
        Button,
    },
    props: ["study", "project", "jobs"],
    setup() {
        const state = reactive({
            count: 0,
            listRecent: {},
        });

        const store = useStore()
        const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb

        const onUploaded = (data) => {
            console.log("onUploaded", data);
        };

        const testAction = () => {
            axios.get("/projects/1/activity").then((response) => {
                console.log("response", response);
                state.listRecent = response?.data?.audit;
            });
        };

        const onBeforeUploadUppy = ({ files, state }) => {
            store.dispatch("updateUppyState", state);
        };

        return {
            ...toRefs(state),
            onUploaded,
            testAction,
            maxFileSize,
            onBeforeUploadUppy,
        };
    },
    methods: {},
};
</script>

<style lang="scss" scoped></style>
