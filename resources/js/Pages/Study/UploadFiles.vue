<template>
    <div>
        <study-content :project="project" :study="study" current="Upload Files">
            <template #study-section>
                <a id="uppy-trigger" @click="isUppyOpen = !isUppyOpen"
                    >Open Uppy</a
                >

                <dashboard-modal
                    :uppy="uppy"
                    :open="isUppyOpen"
                    :props="{ trigger: '#uppy-trigger' }"
                />
            </template>
        </study-content>
    </div>
</template>

<script>
import { reactive, toRefs } from "vue";
import StudyContent from "@/Pages/Study/Content.vue";

import Uppy from "@uppy/core";
import AwsS3Multipart from "@uppy/aws-s3-multipart";
import "@uppy/core/dist/style.css";
import "@uppy/dashboard/dist/style.css";
import { DashboardModal } from "@uppy/vue";

export default {
    components: {
        StudyContent,
        "dashboard-modal": DashboardModal,
    },
    props: ["study", "project", "jobs"],
    setup() {
        const state = reactive({
            count: 0,
            isUppyOpen: false,
        });

        return {
            ...toRefs(state),
        };
    },
    computed: {
        // Uppy Instance
        uppy: () =>
            new Uppy({
                logger: Uppy.debugLogger,
                 onBeforeUpload: (files) => {
                    // We’ll be careful to return a new object, not mutating the original `files`
                    const updatedFiles = {}
                    const myCustomPrefix = 'prefix';
                    Object.keys(files).forEach(fileID => {
                    updatedFiles[fileID] = {
                        ...files[fileID],
                        name: `${myCustomPrefix}__${files[fileID].name}`,
                    }
                    })
                    return updatedFiles
                }
            }).use(AwsS3Multipart, {
                limit: 4,
                companionUrl: "/",
                companionHeaders: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            }),
    },

    beforeDestroy() {
        this.uppy.close();
    },
};
</script>

<style lang="scss" scoped></style>
