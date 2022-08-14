<template>
    <div>
        <div ref="uppy-dashboard-drag-drop-area" class="uploader-class"></div>
    </div>
</template>

<script>
import Uppy from "@uppy/core";
import Dashboard from "@uppy/dashboard";
import ProgressBar from "@uppy/progress-bar";
import AwsS3Multipart from "@uppy/aws-s3-multipart";
import ImageEditor from "@uppy/image-editor";

import { mapActions } from "vuex";

import { v4 as uuidv4 } from "uuid";

export default {
    name: "UppyUploader",
    props: {
        id: {
            type: String,
            default: "uppy",
        },
        value: {
            type: Array,
            default: () => [],
        },
        dashboardHeight: {
            type: [Number, String],
            default: 700,
        },
        dashboardWidth: {
            type: [Number, String],
            default: 920,
        },
        maxFileSize: {
            type: Number,
            default: 500000000,
        },
        allowedFileTypes: {
            type: Array,
            default: null,
        },
        maxNumberOfFiles: {
            type: Number,
            default: 16,
        },
        minNumberOfFiles: {
            type: Number,
            default: null,
        },
        theme: {
            type: String,
            default: "dark",
        },
        locale: {
            type: String,
            default: "en_US",
        },
        pid: {
            type: String,
            default: "3",
        },
        state: {
            type: Object,
            default: () => { return {} },
        },
        stopUpload: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        Uppy,
    },
    data() {
        return {
            uppy: null,
            user: null,
        };
    },
    // watch: {
    //     // whenever question changes, this function will run
    //     state(newState, oldState) {
    //         console.log('watcher!', newState);
    //         this.setUppyState(this.newState);
    //     },
    // },
    mounted() {
        this.setupUppy();
        this.uppy.setMeta({ token: "ab5kjfg" }); // TODO
        this.setupDashboard();
        this.setupEvents();
        this.setupImageEditor();
        this.setupS3Multipart();
        // this.user = JSON.parse(localStorage.getItem("user"));
        // this.onWindowResize();
        this.setUppyState(this.state);
        this.$emit('mounted');
    },
    methods: {
        // ...mapActions(['saveFile']),
        setupUppy() {
            this.uppy = new Uppy({
                id: this.id,
                debug: true,
                autoProceed: false,
                // locale: this.getLocale(),
                restrictions: {
                    maxFileSize: this.maxFileSize,
                    allowedFileTypes: this.allowedFileTypes,
                    maxNumberOfFiles: this.maxNumberOfFiles,
                    minNumberOfFiles: this.minNumberOfFiles,
                },
                onBeforeFileAdded: this.onBeforeFileAdded,
                onBeforeUpload: this.onBeforeUpload,
            });
        },
        setupImageEditor() {
            this.uppy.use(ImageEditor, {
                target: Dashboard,
            });
        },
        setupDashboard() {
            this.uppy.use(Dashboard, {
                target: this.$refs["uppy-dashboard-drag-drop-area"],
                inline: true,
                height: this.dashboardHeight,
                width: this.dashboardWidth,
                hideUploadButton: false,
                showProgressDetails: true,
                note: "Images only, 2–3 files, up to 1 MB (TODO: change limits here)",
                browserBackButtonClose: false,
                // theme: this.theme,
                // locale: this.getLocale(),
                metaFields: (file) => {
                    const fields = [{ id: "name", name: "File name" }];
                    if (file.type.startsWith("image/")) {
                        fields.push({ id: "location", name: "Photo Location" });
                        fields.push({ id: "alt", name: "Alt text" });
                    }
                    return fields;
                },
                animateOpenClose: true,
                proudlyDisplayPoweredByUppy: false,
                autoOpenFileEditor: true,
            });
        },
        setupEvents() {
            this.uppy.on("upload-success", this.handleUploadSuccess);
            this.uppy.on("file-added", this.handleFileAdded);
            this.uppy.on("progress", this.handleProgress);
            this.uppy.on(
                "dashboard:file-edit-complete",
                this.handleFileEditComplete
            );
            this.uppy.on("complete", (result) => {
                console.log("successful files:", result.successful);
                console.log("failed files:", result.failed);
            });
            this.uppy.on("upload-progress", this.handleUpladProgress);
            // window.addEventListener("resize", this.onWindowResize);
        },
        unSetupEvents() {
            this.uppy.off("upload-success", this.handleUploadSuccess);
            this.uppy.off("file-added", this.handleFileAdded);
            this.uppy.off("progress", this.handleProgress);
            this.uppy.off(
                "dashboard:file-edit-complete",
                this.handleFileEditComplete
            );
            this.uppy.off("complete", (result) => {
                console.log("successful files:", result.successful);
                console.log("failed files:", result.failed);
            });
            this.uppy.off("upload-progress", this.handleUpladProgress);
            // window.removeEventListener("resize", this.onWindowResize);
        },
        async handleUploadSuccess(file, data) {
            console.log("upload-success fired", file, data);
            const params = {
                aws: false,
                transformed: false,
            };
            const res = await this.FileDbSave(file, params);
            this.$emit("uploaded", res.status);
        },
        handleFileAdded(file) {
            this.uppy.setFileMeta(file.id, {
                user_id: this.user._id ? this.user._id : "general",
                store_name: [uuidv4(), file.name.split(".").pop()].join("."),
            });
        },
        handleFileEditComplete(file) {
        },
        onBeforeFileAdded(currentFile, files) {
            return currentFile;
        },
        onBeforeUpload(files) {
            this.$emit('update:UppyState', this.uppy.getState());
            this.$emit('onBeforeUpload', {files, state: this.uppy.getState()});
            if (this.stopUpload) { return {} }
        },
        upload() {
            console.log('start upload');
            this.uppy.upload();
        },
        cancelAll() {
            this.uppy.cancelAll();
        },
        setupS3Multipart() {
            this.uppy.use(AwsS3Multipart, {
                limit: 4,
                companionUrl: "/",
                companionHeaders: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });
        },
        // Save file to the MongoDB
        async FileDbSave(
            {
                id,
                size,
                meta: { user_id, type: contenttype, name, store_name },
            },
            { aws, transformed }
        ) {
            const path = [user_id, store_name].join("/");
            const dbData = {
                name: name,
                path,
                size,
                contenttype,
                aws,
                transformed,
                folder: false,
                pid: this.pid,
                fid: Date.now().toString(),
            };
            try {
                await this.saveFile(dbData);
                return { status: true };
            } catch (err) {
                return {
                    status: false,
                    error: err.response
                        ? err.response.data.errors.msg
                        : "UNDEFINED",
                };
            }
        },
        // Check the file in MongoDB:
        async FileDbCheck(name, path) {
            const fix_name = name.split(" ").join("");
            return fix_name;
        },
        setUppySize({ width, height }) {
            if (width) {
                this.uppy.getPlugin("Dashboard").setOptions({
                    width,
                });
            }
            if (height) {
                this.uppy.getPlugin("Dashboard").setOptions({
                    height,
                });
            }
        },
        setUppyState(state) {
            this.uppy.setState(state);
        },
        handleProgress(data) {
            this.$emit('update:UppyState', this.uppy.getState());
            this.$emit("handleProgress", data);
        },
        handleUpladProgress(file, progress) {
            this.$emit('update:UppyState', this.uppy.getState());
            this.$emit("uploadProgress", file, progress);
        },
        closeUppy() {
            this.unSetupEvents();
            this.uppy.close();
        }
    },

    beforeDestroy() {
        this.closeUppy();
    },
};
</script>
