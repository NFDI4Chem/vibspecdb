<template>
  <div class="flex flex-col gap-5 max-w-5xl py5 mx-auto">
    <div class="title2 primary border-b-4">Study cover photo:</div>
    <div class="px6">
      <UploadImage
        class="w-full"
        :item="item"
        :type="type"
        defaultImg="/imgs/study/study.png"
      />
    </div>
    <div class="title2 primary border-b-4">Common information:</div>
    <div class="py-3 flex px6">
      <CommonInfo
        v-model:form="form"
        type="Study"
        :loading="loading"
        @submit="onSubmit"
      />
    </div>
    <div class="title2 primary border-b-4">Metadata:</div>
    <div class="title5 px6">asfdsadf</div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Inertia } from '@inertiajs/inertia'

import UploadImage from '@/Pages/Study/Partials/Elements/UploadImage.vue'
import CommonInfo from '@/Pages/Study/Partials/Elements/CommonInfo.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['item', 'type'])

const loading = ref(false)

const form = ref({
  tags: props?.item.tags_translated,
  name: props?.item.name,
  description: props?.item.description,
})

const onSubmit = () => {
  loading.value = true

  Inertia.put(route('studies.update', props.item?.id), form.value, {
    errorBag: 'studiesUpdate',
    preserveScroll: true,
    onSuccess: () => {
      loading.value = false
      setup_info_notify('The study info has been successfully saved!')
    },
    onError: err => {
      loading.value = false
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {
      loading.value = false
    },
  })
}

const resetForm = () => {
  form_helpers.value.submitted = form_helpers.value.sent = false
}
</script>
