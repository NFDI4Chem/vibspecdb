<template>
  <div class="" :key="imgKey">
    <w-form
      @success="onFormSuccess"
      class="flex flex-row gap-6 justify-between"
    >
      <w-image
        class=""
        :src="imgSrc + 's'"
        fallback="/imgs/study/study.png"
        :width="120"
        :height="120"
      />
      <w-input
        type="file"
        name="image"
        v-model="files"
        class="grow"
        :validators="[() => files.length || 'Please add a file']"
      >
        File
      </w-input>

      <w-button type="submit" md :loading="loading" class="d-flex mla mt2">
        Upload
      </w-button>
    </w-form>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  setup_info_notify,
  setup_error_notify,
} from '@/VueComposable/mixins/useWave'

import { Inertia } from '@inertiajs/inertia'
import { useForm, usePage } from '@inertiajs/inertia-vue3'

const props = defineProps(['item', 'type'])
const imgKey = ref(0)
const files = ref([])
const loading = ref(false)
const errors = ref({ image: '' })
const imgSrc = computed(() => {
  return props?.item?.study_photo_path
})

const onFormSuccess = async () => {
  console.log('try submit')
  errors.value = {}
  loading.value = true

  const form = useForm({
    _method: 'PUT',
    image: files.value[0],
    type: props?.type,
    itemId: props?.item?.id,
  })

  form.post(route('image.store'), {
    errorBag: 'imageStore',
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => {
      loading.value = false
      // TODO check if this is needed and works?
      // Inertia.reload({ only: [props?.type] })
      setup_info_notify('The image has been successfully uploaded!')
    },
    onError: err => {
      loading.value = false
      const message = Object.values(err).join('<br>')
      setup_error_notify(`<div>An error occurred.<br>${message}</div>`)
    },
    onFinish: () => {
      imgKey.value++
      files.value = false
    },
  })
}
</script>

<style lang="scss" scoped></style>
