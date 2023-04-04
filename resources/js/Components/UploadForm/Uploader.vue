<template>
  <div class="relative h-full">
    <UploadFormUppy
      class="sticky top-0 left-0 right-0 bottom-0 h-full"
      pid="3"
      id="JobsUppyInstance"
      v-if="!uppyUploading"
      :maxFileSize="maxFileSize"
      @uploaded="onUploaded"
      @onBeforeUpload="onBeforeUploadUppy"
      ref="UploadFormUppyRef"
      dashboardWidth="100%"
      dashboardHeight="700"
      @handleProgress="handleProgress"
      @uploadProgress="uploadProgress"
      v-model:UppyState="UppyState"
      :stopUpload="true"
      dashboardLimitText="Images only, up to 5 Gb (TODO: change limits here)"
      :baseFolder="baseFolder"
    />
    <UploadProgress
      v-else
      :uploadingText="uploadingText"
      :uploadedText="uploadedText"
      :progress="progress"
      @showModal="showModal"
      @cancelUploading="cancelUploading"
    />
  </div>
</template>

<script setup>
import { reactive, toRefs, ref, onMounted, computed } from 'vue'
import UploadFormUppy from '@/Components/UploadForm/UploadFormUppy.vue'
import UploadProgress from '@/Components/UploadForm/UploadProgress.vue'
import Button from '@/Jetstream/Button.vue'

import {
  ArrowTopRightOnSquareIcon,
  InformationCircleIcon,
} from '@heroicons/vue/24/outline'

import { useStore } from 'vuex'

const uploadingText =
  "We're uploading your files to the main server. Feel free to work on your tasks in the meantime."
const uploadedText = 'Upload completed'

const store = useStore()
const maxFileSize = 5 * 1000 * 1000 * 1000 // Gb / Mb / Kb
const uppyShow = ref(true)
const UploadFormUppyRef = ref()

const props = defineProps({
  baseFolder: {
    type: Object,
    required: false,
  },
})

const progress = computed({
  get() {
    return store.state.Uppy.files.progress
  },
})

const uppyUploading = computed({
  get() {
    return store.state.Uppy.files.uploading
  },
  set(val) {
    store.dispatch('updateFilesData', { uploading: val })
  },
})

const UppyState = computed({
  get() {
    return store.state.Uppy.uppy
  },
  set(val) {
    store.dispatch('updateFilesData', { uppy: val })
  },
})

const onUploaded = data => {
  console.log('log here 1')
  this.$emit('uploaded', data)
}

const delay = time => {
  return new Promise(resolve => setTimeout(resolve, time))
}

const onBeforeUploadUppy = async ({ files, state }) => {
  store.dispatch('updateFilesData', {
    show: true,
    uppy: state,
    viewMode: 'med',
  })
  uppyShow.value = false
  await delay(100)
  store.dispatch('updateFilesData', { uploading: true })
}

const showModal = () => {
  store.dispatch('updateFilesData', { viewMode: 'med' })
}

const handleProgress = () => {}

const uploadProgress = () => {}

const uploadModalStart = () => {
  store.dispatch('updateFilesData', { uploading: true })
}

onMounted(() => {
  // UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 });
})

const setSize = () => {
  UploadFormUppyRef.value.setUppySize({ width: 500, height: 600 })
}

const uploadStart = () => {
  uppyShow.value = true
}

const updateState = () => {}

const cancelUploading = () => {
  uppyUploading.value = false
}
</script>

<style lang="scss" scoped>
.uploading-description {
  max-width: 400px;
}
</style>
