<template>
    <div>
        <div ref="UploadFormUppyRefBase" class="uploader-class"></div>
    </div>
</template>

<script setup>
import Uppy from "@uppy/core";
import Dashboard from "@uppy/dashboard";
import AwsS3Multipart from "@uppy/aws-s3-multipart";
import ImageEditor from "@uppy/image-editor";
import { ref, onMounted, onBeforeUnmount, reactive} from "vue";

const props = defineProps({
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
        default: () => {
            return {};
        },
    },
    stopUpload: {
        type: Boolean,
        default: false,
    },

});

const emit = defineEmits([
    "mounted",
    "uploaded",
    "update:UppyState",
    "handleProgress",
    "uploadProgress",
    "onBeforeUpload",
]);

const UploadFormUppyRefBase = ref();
let  uppy = ref(undefined);

defineExpose({
  uppy,
  UploadFormUppyRefBase
})

const setupUppy = () => {
    uppy = new Uppy({
        id: props.id,
        debug: true,
        autoProceed: false,
        restrictions: {
            maxFileSize: props.maxFileSize,
            allowedFileTypes: props.allowedFileTypes,
            maxNumberOfFiles: props.maxNumberOfFiles,
            minNumberOfFiles: props.minNumberOfFiles,
        },
        onBeforeFileAdded: props.onBeforeFileAdded || onBeforeFileAdded(),
        onBeforeUpload: props.onBeforeUpload || onBeforeUpload(),
    });
};




const setupImageEditor = () => {
    uppy.use(ImageEditor, {
        target: Dashboard,
    });
};

const setupDashboard = () => {
    uppy.use(Dashboard, {
        target: UploadFormUppyRefBase.value,
        inline: true,
        height: props.dashboardHeight,
        width: props.dashboardWidth,
        hideUploadButton: false,
        showProgressDetails: true,
        note: "Images only, 2–3 files, up to 1 MB (TODO: change limits here)",
        browserBackButtonClose: false,
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
        autoOpenFileEditor: false,
    });
};

const setupEvents = () => {
    uppy.on("upload-success", handleUploadSuccess);
    uppy.on("progress", handleProgress);
    uppy.on("dashboard:file-edit-complete", handleFileEditComplete);
    uppy.on("complete", (result) => {
        console.log("successful files:", result.successful);
        console.log("failed files:", result.failed);
    });
    uppy.on("upload-progress", handleUpladProgress);
};

const unSetupEvents = () => {
    uppy.off("upload-success", handleUploadSuccess);
    uppy.off("progress", handleProgress);
    uppy.off("dashboard:file-edit-complete", handleFileEditComplete);
    uppy.off("complete", (result) => {
        console.log("successful files:", result.successful);
        console.log("failed files:", result.failed);
    });
    uppy.off("upload-progress", handleUpladProgress);
};

const handleUploadSuccess = async (file, data) => {
    const params = {
        aws: false,
        transformed: false,
    };
    // const res = await FileDbSave(file, params);
    emit("uploaded", res.status);
};

const handleProgress = (data) => {
    emit("update:UppyState", uppy ? uppy.state : {});
    emit("handleProgress", data);
};

const handleUpladProgress = (file, progress) => {
    emit("update:UppyState", uppy ? uppy.state : {});
    emit("uploadProgress", file, progress);
};

const handleFileEditComplete = (file) => {};

const onBeforeFileAdded = (currentFile, files) => {
    return currentFile;
};

const onBeforeUpload = (files) => {
    emit("update:UppyState", uppy ? uppy.state : {});
    emit("onBeforeUpload", { files, state: uppy ? uppy.state : {} });
    if (props.stopUpload) {
        return {};
    }
};

const upload = () => {
    console.log("start upload");
    uppy.upload();
};

const setupS3Multipart = () => {
    uppy.use(AwsS3Multipart, {
        limit: 4,
        companionUrl: "/",
        companionHeaders: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    });
};

const FileDbCheck = (name, path) => {
    const fix_name = name.split(" ").join("");
    return fix_name;
};

const setUppySize = ({ width, height }) => {
    if (width) {
        uppy.getPlugin("Dashboard").setOptions({
            width,
        });
    }
    if (height) {
        uppy.getPlugin("Dashboard").setOptions({
            height,
        });
    }
};

const setUppyState = (state) => {
    uppy.setState(state);
};

const closeUppy = () => {
    unSetupEvents();
    uppy.close();
};

const FileDbSave = (
    { id, size, meta: { user_id, type: contenttype, name, store_name } },
    { aws, transformed }
) => {
    const path = [user_id, store_name].join("/");
    const dbData = {
        name: name,
        path,
        size,
        contenttype,
        aws,
        transformed,
        folder: false,
        pid: pid,
        fid: Date.now().toString(),
    };
    try {
        // await saveFile(dbData);
        return { status: true };
    } catch (err) {
        return {
            status: false,
            error: err.response ? err.response.data.errors.msg : "UNDEFINED",
        };
    }
};


onMounted(() => {
    setupUppy();
    uppy.setMeta({ token: "ab5kjfg" });
    setupDashboard();
    setupEvents();
    setupImageEditor();
    setupS3Multipart();
    setUppyState(props.state);
    emit("mounted");
});

onBeforeUnmount(() => {
    closeUppy();
});



</script>

<script>
export default {
    name: "UppyUploader",
};
</script>
