<template>
    <div>
        <study-content :project="project" :study="study" current="Jobs">
            <template #study-section>
                <UploadFormUppy
                    pid="3"
                    id="JobsUppyInstance"
                    v-if="uppyShow"
                    :maxFileSize="maxFileSize"
                    @uploaded="onUploaded"
                    @onBeforeUpload="onBeforeUploadUppy"
                    ref="UploadFormUppyRef"
                    @handleProgress="handleProgress"
                    @uploadProgress="uploadProgress"
                    v-model:UppyState="UppyState"
                    :stopUpload="true"
                />
                <div class="w-3/4 p-2">
                    <div class="flex flex-row justify-between gap-2">
                        <Button @click="uploadStart">Upload</Button>
                        <!-- <Button @click="uploadModalStart">Start Upload Modal</Button> -->
                        <!-- <Button @click="updateState">Update State</Button> -->
                        <!-- <Button @click="testAction">RunMe</Button> -->
                        <!-- <Button @click="setSize">Size</Button> -->
                    </div>
                </div>
                <!-- {{UppyState}} -->
                <!-- {{ listRecent }} -->
            </template>
        </study-content>
    </div>
</template>

<script>
import { reactive, toRefs, ref, onMounted, computed } from "vue";
import StudyContent from "@/Pages/Study/Content.vue";
import UploadFormUppy from "@/Components/UploadForm/UploadFormUppy.vue";
import Button from "@/Jetstream/Button.vue";

import { useStore } from "vuex";

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

        const UppyState = computed({
            get() {
                return store.state.Uppy.uppy;
            },
            set(val) {
                // console.log('set Jobs UppyState');
                store.dispatch("updateUppyState", val);
            },
        });

        const store = useStore();
        const maxFileSize = 5 * 1000 * 1000 * 1000; // Gb / Mb / Kb
        const uppyShow = ref(false);
        const UploadFormUppyRef = ref();

        const onUploaded = (data) => {
            console.log("onUploaded", data);
        };

        const testAction = () => {
            axios.get("/projects/1/activity").then((response) => {
                console.log("response", response);
                state.listRecent = response?.data?.audit;
            });
        };

        const delay = (time) => {
          return new Promise(resolve => setTimeout(resolve, time));
        }

        const onBeforeUploadUppy = async ({ files, state }) => {
            store.dispatch("updateUppyState", state);
            store.dispatch("updateShow", { files: true });
            uppyShow.value = false;
            await delay(100);
            store.dispatch("updateStartUpload", { files: true });
        };

        const handleProgress = () => {
          
        }

        const uploadProgress = () => {

        }

        const uploadModalStart = () => {
          store.dispatch("updateStartUpload", { files: true });
        }

        onMounted(() => {
            // UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 });
        });

        const setSize = () => {
            UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 });
        };

        const uploadStart = () => {
          uppyShow.value = true;
        }

        const updateState = () => {

        }

        return {
            ...toRefs(state),
            onUploaded,
            testAction,
            maxFileSize,
            uppyShow,
            setSize,
            UploadFormUppyRef,
            UppyState,
            uploadStart, 
            onBeforeUploadUppy,
            handleProgress,
            uploadProgress,
            updateState,
            uploadModalStart
        };
    },
    methods: {},
};
</script>

<style lang="scss" scoped></style>
