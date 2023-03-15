<template>
  <div class="flex flex-col gap-6 max-w-5xl py5 mx-auto">
    <div class="flex flex-row flex-start gap-2">
      <div class="mb-auto">
        <StudyInfoHelper
          :right="true"
          text="Section to update Study cover image."
        />
      </div>
      <div class="title2 primary border-b-2">Study cover photo:</div>
    </div>
    <div class="px6">
      <UploadImage
        class="w-full"
        :item="item"
        :type="type"
        defaultImg="/imgs/study/study.png"
      />
    </div>

    <div class="spacer my2"></div>

    <div class="flex flex-row flex-start gap-2">
      <div class="mb-auto">
        <StudyInfoHelper
          :right="true"
          text="Section to update the Study Name, Description and Tags."
        />
      </div>
      <div class="title2 primary border-b-2">Common information:</div>
    </div>
    <div class="py-3 flex px6">
      <CommonInfo
        :formdata="formdata"
        type="Study"
        :loading="loading"
        @submit="onSubmit"
        @update="onUpdate"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Inertia } from '@inertiajs/inertia'

import UploadImage from '@/Pages/Study/Partials/Elements/UploadImage.vue'
import CommonInfo from '@/Pages/Study/Partials/Elements/CommonInfo.vue'

import StudyInfoHelper from '@/Components/Popper/Study/StudyInfoHelper.vue'

import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

const props = defineProps(['item', 'type'])

const loading = ref(false)

const formdata = ref({
  tags: props?.item.tags_translated,
  name: props?.item.name,
  description: props?.item.description,
})

const onUpdate = changes => {
  formdata.value = {
    ...formdata.value,
    ...changes,
  }
}

const onSubmit = () => {
  loading.value = true

  Inertia.put(route('studies.update', props.item?.id), formdata.value, {
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
