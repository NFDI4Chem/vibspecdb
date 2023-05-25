<template>
  <div>
    <div
      class="flex flex-row flex-wrap justify-center gap-10 items-center py-6 px-6"
    >
      <div v-if="false">
        <w-progress class="ma1" circle v-model="progress" size="8em">
          <strong>{{ progress }}%</strong>
        </w-progress>
      </div>
      <div class="w-full">
        <div
          v-if="loading?.minio_upload?.loading || loading?.minio_upload?.done"
          class="flex flex-row align-middle justify-between"
        >
          <div>Files uploading:</div>
          <w-progress
            v-if="!loading?.minio_upload?.done"
            class="rounded-none text-xs w-1/2 mt-[8px]"
            size="0.6rem"
            color="light-blue-dark3"
          />
          <w-icon v-else md color="cyan-dark2">
            mdi mdi-check-circle-outline
          </w-icon>
        </div>

        <div class="flex flex-row align-middle justify-between">
          <div>Saving to database / Zip extraction:</div>
          <w-progress
            v-if="!loading?.saving_to_database?.done"
            class="rounded-none text-xs w-1/2 mt-[8px]"
            size="0.6rem"
            color="light-blue-dark3"
          />
          <w-icon v-else md color="cyan-dark2">
            mdi mdi-check-circle-outline
          </w-icon>
        </div>

        <div
          v-if="
            loading?.zip_extracting?.loading || loading?.zip_extracting?.done
          "
          class="flex flex-row align-middle justify-between"
        >
          <div>Extracting Archive:</div>
          <w-progress
            v-if="!loading?.zip_extracting?.done"
            class="rounded-none text-xs w-1/2 mt-[8px]"
            size="0.6rem"
            color="light-blue-dark3"
          />
          <w-icon v-else md color="cyan-dark2 mt-[8px]">
            mdi mdi-check-circle-outline
          </w-icon>
        </div>
      </div>
      <div
        class="w-full uploading-description flex flex-col items-center gap-5"
      >
        <div class="font-bold text-4xl text-gray-700 text-center">
          {{ progress < 100 ? 'Uploading files...' : 'Files Uploaded' }}
        </div>
        <div class="text-center text-lg p-5 bg-gray-200 w-full">
          {{ progress < 100 ? uploadingText : uploadedText }}
        </div>

        <div class="w-full flex justify-between">
          <w-button
            v-if="progress < 100"
            @click="cancelUploading"
            bg-color="secondary"
          >
            Cancel upload
          </w-button>
          <w-button v-else @click="showModal" bg-color="secondary">
            Upload More
          </w-button>

          <w-button @click="showModal" bg-color="primary">
            Show Modal <w-icon class="ml2" sm>mdi mdi-open-in-new</w-icon>
          </w-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import RadialProgressBar from 'vue3-radial-progress'

const props = defineProps([
  'progress',
  'uploadingText',
  'uploadedText',
  'loading',
])
const emit = defineEmits(['showModal', 'cancelUploading'])

const showModal = () => {
  emit('showModal')
}

const cancelUploading = () => {
  emit('cancelUploading')
}
</script>
