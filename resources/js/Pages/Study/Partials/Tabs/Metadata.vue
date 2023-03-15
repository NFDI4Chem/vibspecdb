<template>
  <div class="flex flex-col gap-5 max-w-5xl py5 mx-auto">
    <div class="flex flex-row justify-between pb2">
      <div class="border-b-2 flex flex-row flex-start gap-2">
        <div class="mb-auto">
          <StudyInfoHelper
            :right="true"
            text="Section to update the Study Name, Description and Tags."
          />
        </div>
        <div class="title2 primary">Custom Metadata:</div>
      </div>
      <w-button md :disabled="loading" class="" @click="addMetaEntry">
        Add New
      </w-button>
    </div>
    <div class="title5 px6">
      <MetaCustom v-model:metadata="metadata" :disabled="loading" />
      <div v-if="metadata.length" class="flex flex-row justify-end my6">
        <w-button
          lg
          :disabled="false"
          :loading="loading"
          class=""
          @click="saveMetadata"
        >
          Save
        </w-button>
      </div>
    </div>
    <!-- {{ meta_formatted }} -->
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'

import MetaCustom from '@/Pages/Study/Partials/Elements/MetaCustom.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['item', 'type'])

const loading = ref(false)
const metadata = ref(
  Object.entries(props?.item?.metadata).map(e => ({
    param: [e[0]],
    value: e[1],
  })),
)

const addMetaEntry = () => {
  metadata.value.push({
    param: '',
    value: '',
  })
}

const meta_formatted = computed(() => {
  let _metadata = {}
  metadata.value
    .filter(meta => {
      return !!meta.param
    })
    .map(meta => {
      _metadata[meta.param] = meta.value
      return meta
    })
  return _metadata
})

const saveMetadata = () => {
  loading.value = true

  Inertia.put(
    route('studies.update', props.item?.id),
    {
      metadata: meta_formatted.value,
    },
    {
      errorBag: 'studiesUpdate',
      preserveScroll: true,
      onSuccess: () => {
        loading.value = false
        setup_info_notify('The study Metadata has been successfully saved!')
      },
      onError: err => {
        loading.value = false
        const message = Object.values(err).join('<br>')
        setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
      },
      onFinish: () => {
        loading.value = false
      },
    },
  )
}

// const metadata = ref([
//   {
//     key: 'painted',
//     value: 'blue',
//   },
//   {
//     key: 'counted',
//     value: 3,
//   },
//   {
//     key: 'family',
//     value: ['bro', 'sister', 'bro'],
//   },
// ])
</script>

<style lang="scss" scoped></style>
