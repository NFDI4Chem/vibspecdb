<template>
  <div
    v-if="show"
    as="div"
    class="z-20 fixed bottom-0 right-0"
    @close="show = true"
    :open="show"
    :class="{
      ['mini-preview']: view === 'min',
      ['med-preview']: view === 'med',
      ['max-preview']: view === 'max',
    }"
  >
    <!-- {{ activeItem }} -->
    <ModalHeader
      :show="true"
      :progress="progress"
      @changeView="changeViewModal"
      @closeView="closeViewModal"
      :view="view"
      :title="combinedTitle"
      class="modal-header"
    />

    <!-- <button @click="getUppyStatus">Get It</button> -->

    <div
      class="pointer-events-auto"
      v-show="view !== 'min'"
      :class="{
        ['mini-preview']: view === 'min',
        ['med-preview']: view === 'med',
        ['max-preview']: view === 'max',
      }"
    >
      <div
        class="flex flex-col overflow-y-auto bg-white shadow-xl h-full w-full"
      >
        <div class="relative flex-1">
          <div class="absolute inset-0 h-full">
            <UploadFormUppy
              pid="3"
              @uploaded="onUploaded"
              @completed="onCompleted"
              @error="onError"
              @upload-error="onUploadError"
              :maxFileSize="maxFileSize"
              @handleProgress="onHandleProgress"
              @uploadProgress="onUploadProgress"
              @onBeforeUpload="onBeforeUpload"
              @onBeforeRetry="onBeforeRetry"
              :title="title"
              ref="UploadFormUppyRef"
              @mounted="onUppyMounted"
              :state="uppyState"
              :stopUpload="false"
              dashboardLimitText="Images only, up to 5 Gb (TODO: change limits here)"
              :baseFolder="activeItem"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { Dialog, DialogPanel } from '@headlessui/vue'
import { MinusIcon } from '@heroicons/vue/24/outline'

import { useForm, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import UploadFormUppy from '@/Components/UploadForm/UploadFormUppy.vue'
import ModalHeader from './ModalHeader.vue'

import { useFiles, loading } from '@/VueComposable/useFiles'
import { saveDBfiles, loadingStatus } from '@/VueComposable/useFilesTree'

import { useStore } from 'vuex'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const { extractzip, saveFile } = useFiles()
const store = useStore()

// const show = ref(true);
// const view = ref("med");
const progress = ref(0)
const maxFileSize = 5 * 1000 * 1000 * 1000 // Gb / Mb / Kb
const UploadFormUppyRef = ref()

const show = computed({
  get() {
    return store.state.Uppy.files.show
  },
  set(val) {
    store.dispatch('updateFilesData', { show: val })
  },
})

const activeItem = computed({
  get() {
    return store.getters.Treefiles.activeItem
  },
})

const view = computed({
  get() {
    return store.state.Uppy.files.viewMode
  },
  set(val) {
    store.dispatch('updateFilesData', { viewMode: val })
  },
})

const uppyState = computed({
  get() {
    // console.log('Files Modal changes get')
    return store.state.Uppy.files.uppy
  },
  set(val) {
    // console.log('Files Modal changes', val)
    // store.dispatch("updateFilesData", {uppy: val});
  },
})

const uploaded = computed({
  get() {
    return store.state.Uppy.files.uploaded
  },
  set(val) {
    store.dispatch('updateFilesData', { uploaded: val })
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

watch(uppyUploading, (newValue, oldValue) => {
  if (newValue) {
    UploadFormUppyRef.value.upload()
  } else {
    closeViewModal()
  }
})

const props = defineProps({
  title: {
    type: String,
    default: 'Upload Files',
  },
})

const combinedTitle = computed(() => {
  return !uploaded.value ? `Uploading ...` : `Files Uploaded`
})

const onUppyMounted = () => {
  updateUppySize('med')
  // store.dispatch("updateFilesData", { uploaded: false });
  uploaded.value = false
}

const updateUppySize = type => {
  let size = { width: 0, height: 0 }
  switch (type) {
    case 'min':
      size = { width: 0, height: 0 }
      break
    case 'med':
      size = { width: 500, height: 600 }
      break
    case 'max':
      size = {
        width: window.innerWidth,
        height: window.innerHeight - 50,
      }
      break
    default:
      size = { width: 0, height: 0 }
      break
  }
  UploadFormUppyRef.value.setUppySize(size)
}

const changeViewModal = type => {
  updateUppySize(type)
  view.value = type
  store.dispatch('updateFilesData', { viewMode: type })
}

const closeViewModal = () => {
  show.value = false
  store.dispatch('updateFilesData', { uploading: false, progress: 0 })
  UploadFormUppyRef.value.cancelAll()
}

/// reload Inertia
const study = computed(() => usePage().props.value.study)
const MakeReload = () => {
  Inertia.reload({ only: ['files'] })
}

const onCompleted = ({ failed, successful }) => {
  if (failed?.length > 0) {
    setup_error_notify(
      'Not all files where uploaded. Please check upload status info.',
    )
  } else if (successful?.length > 0) {
    saveDBfiles(successful)
  }
}

const onError = err => {
  // console.log('onError', err)
  // setup_error_notify('Upload failed: ' + err)
}
const onUploadError = (file, error, response) => {
  // console.log('onUploadError', file, error, response)
  // setup_error_notify('Upload failed: ' + err)
}

const onUploaded = async (file, data) => {
  // await onAddFile(file)
  uploaded.value = true
}

const onBeforeRetry = () => {
  // store.dispatch("updateFilesData", { uploaded: false });
  uploaded.value = false
}

const onBeforeUpload = () => {
  // store.dispatch("updateFilesData", { uploaded: false });

  uploaded.value = false
}

const onHandleProgress = prog => {
  if (prog === 0) {
    loading.value = loadingStatus('clear_all')
    loading.value = loadingStatus('minio_upload_loading')
  }

  progress.value = prog
  store.dispatch('updateFilesData', { progress: prog })
  if (prog === 100) {
    loading.value = loadingStatus('minio_upload_done')
  }
}

const onUploadProgress = (file, { uploader, bytesUploaded, bytesTotal }) => {
  //   console.log('onUploadProgress', uploader, bytesUploaded, bytesTotal)
}

/*
const getUppyStatus = () => {
    console.log("test", store.state.Uppy.files.uppy);
    UploadFormUppyRef.value.setUppyState(store.state.Uppy.uppy);
    view.value = "max";
    updateUppySize("max");
};
*/

// defineExpose({ UploadFormUppyRef })
</script>

<style lang="scss" scoped>
.max-preview {
  width: 100vw;
  height: calc(100vh);
}
.med-preview {
  height: 650px;
  min-width: 300px;
}
.mini-preview {
  height: 50px;
  width: 350px;
}
</style>
