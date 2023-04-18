<template>
  <div>
    <div
      ref="uppy-dashboard-drag-drop-area"
      class="uploader-class h-full"
    ></div>
  </div>
</template>

<script>
import Uppy from '@uppy/core'
import Dashboard from '@uppy/dashboard'
import ProgressBar from '@uppy/progress-bar'
import AwsS3Multipart from '@uppy/aws-s3-multipart'
import ImageEditor from '@uppy/image-editor'

import { mapActions } from 'vuex'

import { v4 as uuidv4 } from 'uuid'

export default {
  name: 'UppyUploader',
  inject: ['csrf_token'],
  props: {
    id: {
      type: String,
      default: 'uppy',
    },
    dashboardLimitText: {
      type: String,
      default: 'Limit Here',
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
      default: 200,
    },
    minNumberOfFiles: {
      type: Number,
      default: null,
    },
    theme: {
      type: String,
      default: 'dark',
    },
    locale: {
      type: String,
      default: 'en_US',
    },
    pid: {
      type: String,
      default: '3',
    },
    state: {
      type: Object,
      default: () => {
        return {}
      },
    },
    stopUpload: {
      type: Boolean,
      default: false,
    },
    baseFolder: {
      type: Object,
      required: false,
    },
  },
  components: {
    Uppy,
  },
  data() {
    return {
      uppy: null,
      user: null,
    }
  },
  mounted() {
    this.setupUppy()
    this.setupDashboard()
    this.setupEvents()
    this.setupImageEditor()
    this.setupS3Multipart()
    // this.user = JSON.parse(localStorage.getItem("user"));
    // this.onWindowResize();
    this.setUppyState(this.state)
    this.$emit('mounted')
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
      })
    },
    setupImageEditor() {
      this.uppy.use(ImageEditor, {
        target: Dashboard,
      })
    },
    setupDashboard() {
      this.uppy.use(Dashboard, {
        target: this.$refs['uppy-dashboard-drag-drop-area'],
        inline: true,
        height: this.dashboardHeight,
        width: this.dashboardWidth,
        hideUploadButton: false,
        showProgressDetails: true,
        note: this.dashboardLimitText,
        browserBackButtonClose: false,
        // theme: this.theme,
        // locale: this.getLocale(),
        metaFields: file => {
          /*
                    const fields = [
                        {
                            path: [
                                "project" + $page.props.project.id,
                                "study" + $page.props.study.id,
                            ].join("/"),
                        },
                    ];
                    if (file.type.startsWith("image/")) {
                        fields.push({ id: "location", name: "Photo Location" });
                        fields.push({ id: "alt", name: "Alt text" });
                    }
                    return fields;
                    */
        },
        animateOpenClose: true,
        proudlyDisplayPoweredByUppy: false,
        autoOpenFileEditor: false,
      })
    },
    setupEvents() {
      this.uppy.on('upload-success', this.handleUploadSuccess)
      this.uppy.on('file-added', this.handleFileAdded)
      this.uppy.on('upload-retry', this.onBeforeRetry)
      this.uppy.on('progress', this.handleProgress)
      this.uppy.on('dashboard:file-edit-complete', this.handleFileEditComplete)
      this.uppy.on('complete', this.handleComplete)
      this.uppy.on('upload-progress', this.handleUpladProgress)
      this.uppy.on('error', this.handleError)
      this.uppy.on('upload-error', this.handleUploadError)
      // window.addEventListener("resize", this.onWindowResize);
    },
    unSetupEvents() {
      this.uppy.off('upload-success', this.handleUploadSuccess)
      this.uppy.off('file-added', this.handleFileAdded)
      this.uppy.off('upload-retry', this.onBeforeRetry)
      this.uppy.off('progress', this.handleProgress)
      this.uppy.off('dashboard:file-edit-complete', this.handleFileEditComplete)
      this.uppy.off('complete', this.handleComplete)
      this.uppy.off('upload-progress', this.handleUpladProgress)
      this.uppy.off('error', this.handleError)
      this.uppy.off('upload-error', this.handleUploadError)
      // window.removeEventListener("resize", this.onWindowResize);
    },
    handleError(err) {
      this.$emit('error', err?.stack)
    },
    handleUploadError(file, error, response) {
      this.$emit('upload-error', file, error, response)
    },
    handleUploadSuccess(file, data) {
      this.$emit('uploaded', file, data)
    },
    handleFileAdded(file) {
      const { id, level } = this.baseFolder
      this.uppy.setFileMeta(file.id, {
        uppyid: file.id,
        parent_id: id,
        level: level ? level : 1,
        project_id: this.$page.props.project.id,
        study_id: this.$page.props.study.id,
        size: file.size,
        ftype: file.type,
      })
    },
    handleFileEditComplete(file) {},
    handleComplete(result) {
      this.$emit('completed', {
        successful: result.successful,
        failed: result.failed,
      })
    },
    onBeforeFileAdded(currentFile, files) {
      return currentFile
    },
    onBeforeRetry() {
      this.$emit('onBeforeRetry')
    },
    onBeforeUpload(files) {
      // console.log('files', files)
      this.$emit('update:UppyState', this.uppy.getState())
      this.$emit('onBeforeUpload', {
        files,
        state: this.uppy.getState(),
      })
      if (this.stopUpload) {
        return {}
      }
    },
    upload() {
      // console.log("start upload", this.baseFolder);
      this.uppy.upload()
    },
    cancelAll() {
      this.uppy.cancelAll()
    },
    setupS3Multipart() {
      this.uppy.use(AwsS3Multipart, {
        limit: 4,
        companionUrl: '/',
        companionHeaders: {
          'X-CSRF-TOKEN': this.csrf_token,
        },
      })
    },
    setUppySize({ width, height }) {
      if (width) {
        this.uppy.getPlugin('Dashboard').setOptions({
          width,
        })
      }
      if (height) {
        this.uppy.getPlugin('Dashboard').setOptions({
          height,
        })
      }
    },
    setUppyState(state) {
      this.uppy.setState(state)
    },
    handleProgress(data) {
      this.$emit('update:UppyState', this.uppy.getState())
      this.$emit('handleProgress', data)
    },
    handleUpladProgress(file, progress) {
      this.$emit('update:UppyState', this.uppy.getState())
      this.$emit('uploadProgress', file, progress)
    },
    closeUppy() {
      this.unSetupEvents()
      this.uppy.close()
    },
  },

  beforeDestroy() {
    this.closeUppy()
  },
}
</script>
