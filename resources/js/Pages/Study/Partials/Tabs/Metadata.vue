<template>
  <div class="flex flex-col gap-12 max-w-5xl py5 mx-auto">
    <MetaForm
      :loading="loading_required"
      :removeable="false"
      :addable="false"
      :editablekey="false"
      title="Required Metadata: *"
      helperText="Required metadata for each study."
      @onSubmit="saveMetadata('required')"
      @onAddEntry="addMetaEntryRequired"
      v-model:metadata="required_meta"
    />
    <MetaForm
      :loading="loading"
      :removeable="true"
      :addable="true"
      :editablekey="true"
      title="Custom Metadata:"
      helperText="Your custom metadata key-value pairs."
      @onSubmit="saveMetadata('custom')"
      @onAddEntry="addMetaEntry"
      v-model:metadata="metadata"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Inertia } from '@inertiajs/inertia'

import MetaForm from '@/Pages/Study/Partials/Elements/Metadata/MetaForm.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const ObjectToArray = array => {
  return Object.entries(array).map(meta => ({
    param: meta[0],
    value: meta[1],
  }))
}

const props = defineProps(['item', 'type'])

const loading_required = ref(false)
const required_meta = ref(ObjectToArray(props?.item?.required_meta))

const loading = ref(false)
const meta = ObjectToArray(props?.item?.metadata)
const rmeta = ObjectToArray(props?.item?.required_meta)
const custom_metadata = () => {
  return meta.filter(m => {
    return !rmeta
      .map(mr => {
        return mr.param
      })
      .includes(m.param)
  })
}
const metadata = ref(custom_metadata())

const addMetaEntry = () => {
  metadata.value.push({
    param: '',
    value: '',
  })
}

const meta_formatted = computed(() => {
  let _metadata = {}
  metadata.value
    .concat(required_meta.value)
    .filter(meta => {
      return !!meta.param
    })
    .map(meta => {
      _metadata[meta.param] = meta.value
      return meta
    })
  return _metadata
})

const setLoading = (type = 'custom', value = false) => {
  if (type === 'custom') {
    loading.value = value
  }
  if (type === 'required') {
    loading_required.value = value
  }
}

const saveMetadata = (type = 'custom') => {
  setLoading(type, true)

  Inertia.put(
    route('studies.update', props.item?.id),
    {
      metadata: meta_formatted.value,
    },
    {
      errorBag: 'studiesUpdate',
      preserveScroll: true,
      onSuccess: () => {
        setLoading(type, false)
        setup_info_notify('The study Metadata has been successfully saved!')
      },
      onError: err => {
        setLoading(type, false)
        const message = Object.values(err).join('<br>')
        setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
      },
      onFinish: () => {
        setLoading(type, false)
      },
    },
  )
}
</script>
